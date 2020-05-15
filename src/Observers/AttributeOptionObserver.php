<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Observers;

use TechDivision\Import\Utils\BackendTypeKeys;
use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\EntityTypeCodes;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;
use TechDivision\Import\Observers\AttributeLoaderInterface;
use TechDivision\Import\Observers\DynamicAttributeObserverInterface;

/**
 * Observer that create's the attribute options found in the additional CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
     */
    public function __construct(
        AttributeBunchProcessorInterface $attributeBunchProcessor,
        AttributeLoaderInterface $attributeLoader = null
    ) {
        $this->attributeBunchProcessor = $attributeBunchProcessor;
        $this->attributeLoader = $attributeLoader;
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
