<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Observers;

use TechDivision\Import\Dbal\Utils\EntityStatus;
use TechDivision\Import\Utils\BackendTypeKeys;
use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\EntityTypeCodes;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;
use TechDivision\Import\Observers\StateDetectorInterface;
use TechDivision\Import\Observers\AttributeLoaderInterface;
use TechDivision\Import\Observers\DynamicAttributeObserverInterface;
use TechDivision\Import\Observers\EntityMergers\EntityMergerInterface;

/**
 * Observer that create's the attribute options found in the additional CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionObserver extends AbstractAttributeImportObserver implements DynamicAttributeObserverInterface
{

    /**
     * The attribute processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

    /**
     * The attribute loader instance.
     *
     * @var \TechDivision\Import\Observers\AttributeLoaderInterface
     */
    protected $attributeLoader;

    /**
     * The collection with entity merger instances.
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $entityMergers;

    /**
     * Initialize the dedicated column.
     *
     * @var array
     */
    protected $columns = array(MemberNames::SORT_ORDER => array(ColumnKeys::SORT_ORDER, BackendTypeKeys::BACKEND_TYPE_INT));

    /**
     * Initializes the observer with the passed subject instance.
     *
     * @param \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface $attributeBunchProcessor The attribute bunch processor instance
     * @param \TechDivision\Import\Observers\AttributeLoaderInterface                  $attributeLoader         The attribute loader instance
     * @param \TechDivision\Import\Observers\EntityMergers\EntityMergerInterface       $entityMerger            The entity merger instance
     * @param \TechDivision\Import\Observers\StateDetectorInterface|null               $stateDetector           The state detector instance to use
     */
    public function __construct(
        AttributeBunchProcessorInterface $attributeBunchProcessor,
        AttributeLoaderInterface $attributeLoader = null,
        EntityMergerInterface $entityMerger = null,
        StateDetectorInterface $stateDetector = null
    ) {

            // initialize the bunch processor, the attribute loader and the entity merger instance
        $this->attributeBunchProcessor = $attributeBunchProcessor;
        $this->attributeLoader = $attributeLoader;
        $this->entityMerger = $entityMerger;

        // pass the state detector to the parent method
        parent::__construct($stateDetector);
    }

    /**
     * Process the observer's business logic.
     *
     * @return void
     */
    protected function process()
    {

        // query whether or not, we've found a new attribute code => means we've found a new attribute
        if ($this->hasBeenProcessed($this->getValue(ColumnKeys::ATTRIBUTE_CODE), $this->getValue(ColumnKeys::VALUE))) {
            return;
        }

        // prepare the store view code
        $this->prepareStoreViewCode();

        // prepare the attribue values
        $attributeOption = $this->initializeAttribute($this->prepareDynamicAttributes());

        // insert the attribute option and set the option ID
        $this->setLastOptionId($this->persistAttributeOption($attributeOption));
    }

    /**
     * Merge's and return's the entity with the passed attributes and set's the
     * passed status.
     *
     * @param array       $entity        The entity to merge the attributes into
     * @param array       $attr          The attributes to be merged
     * @param string|null $changeSetName The change set name to use
     *
     * @return array The merged entity
     * @todo https://github.com/techdivision/import/issues/179
     */
    protected function mergeEntity(array $entity, array $attr, $changeSetName = null)
    {
        return array_merge(
            $entity,
            $this->entityMerger ? $this->entityMerger->merge($this, $entity, $attr) : $attr,
            array(EntityStatus::MEMBER_NAME => $this->detectState($entity, $attr, $changeSetName))
        );
    }

    /**
     * Appends the dynamic to the static attributes for the EAV attribute
     * and returns them.
     *
     * @return array The array with all available attributes
     */
    protected function prepareDynamicAttributes()
    {
        return array_merge($this->prepareAttributes(), $this->attributeLoader ? $this->attributeLoader->load($this, $this->columns) : array());
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the attribute ID
        $attribute = $this->loadAttributeByEntityTypeIdAndAttributeCode($this->getEntityTypeId(), $this->getValue(ColumnKeys::ATTRIBUTE_CODE));
        $attributeId = $attribute[MemberNames::ATTRIBUTE_ID];

        // return the prepared attribute option
        return $this->initializeEntity(
            $this->loadRawEntity(
                array(MemberNames::ATTRIBUTE_ID => $attributeId)
            )
        );
    }

    /**
     * Load's and return's a raw customer entity without primary key but the mandatory members only and nulled values.
     *
     * @param array $data An array with data that will be used to initialize the raw entity with
     *
     * @return array The initialized entity
     */
    protected function loadRawEntity(array $data = array())
    {
        return $this->getAttributeBunchProcessor()->loadRawEntity(EntityTypeCodes::EAV_ATTRIBUTE_OPTION, $data);
    }

    /**
     * Initialize the EAV attribute option with the passed attributes and returns an instance.
     *
     * @param array $attr The EAV attribute option attributes
     *
     * @return array The initialized EAV attribute option
     */
    protected function initializeAttribute(array $attr)
    {
        return $attr;
    }

    /**
     * Return's the attribute bunch processor instance.
     *
     * @return \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface The attribute bunch processor instance
     */
    protected function getAttributeBunchProcessor()
    {
        return $this->attributeBunchProcessor;
    }

    /**
     * Queries whether or not the attribute with the passed code/value has already been processed.
     *
     * @param string $attributeCode The attribute code to check
     * @param string $value         The option value to check
     *
     * @return boolean TRUE if the path has been processed, else FALSE
     */
    protected function hasBeenProcessed($attributeCode, $value)
    {
        return $this->getSubject()->hasBeenProcessed($attributeCode, $value);
    }

    /**
     * Set's the ID of the option that has been created recently.
     *
     * @param integer $lastOptionId The option ID
     *
     * @return void
     */
    protected function setLastOptionId($lastOptionId)
    {
        $this->getSubject()->setLastOptionId($lastOptionId);
    }

    /**
     * Return's the EAV attribute with the passed entity type ID and code.
     *
     * @param integer $entityTypeId  The entity type ID of the EAV attribute to return
     * @param string  $attributeCode The code of the EAV attribute to return
     *
     * @return array The EAV attribute
     */
    public function loadAttributeByEntityTypeIdAndAttributeCode($entityTypeId, $attributeCode)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeByEntityTypeIdAndAttributeCode($entityTypeId, $attributeCode);
    }

    /**
     * Persist the passed attribute option.
     *
     * @param array $attributeOption The attribute option to persist
     *
     * @return void
     */
    protected function persistAttributeOption(array $attributeOption)
    {
        return $this->getAttributeBunchProcessor()->persistAttributeOption($attributeOption);
    }
}
