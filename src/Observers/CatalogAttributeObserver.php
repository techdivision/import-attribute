<?php

/**
 * TechDivision\Import\Attribute\Observers\CatalogAttributeObserver
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
use TechDivision\Import\Loaders\LoaderInterface;
use TechDivision\Import\Observers\StateDetectorInterface;
use TechDivision\Import\Observers\EntityMergers\EntityMergerInterface;
use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\EntityTypeCodes;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * Observer that create's the EAV catalog attribute itself.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class CatalogAttributeObserver extends AbstractAttributeImportObserver
{

    /**
     * The key for the additional data containing the swatch type.
     *
     * @var string
     */
    const SWATCH_INPUT_TYPE = 'swatch_input_type';

    /**
     * The available swatch types.
     *
     * @var array
     */
    protected $swatchTypes = array('text', 'visual', 'image');

    /**
     * The attribute processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

    /**
     * The collection with entity merger instances.
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $entityMerger;

    /**
     * Array with virtual column name mappings (this is a temporary
     * solution till techdivision/import#179 as been implemented).
     *
     * @var array
     * @todo https://github.com/techdivision/import/issues/179
     */
    protected $reverseHeaderMappings = array();

    /**
     * Initializes the observer with the passed subject instance.
     *
     * @param \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface $attributeBunchProcessor The attribute bunch processor instance
     * @param \TechDivision\Import\Observers\EntityMergers\EntityMergerInterface|null       $entityMerger            The entity merger instance
     * @param \TechDivision\Import\Loaders\LoaderInterface|null                        $headerMappingLoader     The loader for the virtual mappings
     * @param \TechDivision\Import\Observers\StateDetectorInterface|null               $stateDetector           The state detector instance to use
     */
    public function __construct(
        AttributeBunchProcessorInterface $attributeBunchProcessor,
        ?EntityMergerInterface $entityMerger = null,
        ?LoaderInterface $headerMappingLoader = null,
        ?StateDetectorInterface $stateDetector = null
    ) {

        // initialize the bunch processor and the entity merger instance
        $this->attributeBunchProcessor = $attributeBunchProcessor;
        $this->entityMerger = $entityMerger;

        // initialize the reverse header mappings table > CSV column name
        $this->reverseHeaderMappings = array_merge(
            $this->reverseHeaderMappings,
            $headerMappingLoader ? array_flip($headerMappingLoader->load()) : array()
        );

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
        if ($this->hasBeenProcessed($this->getValue(ColumnKeys::ATTRIBUTE_CODE))) {
            return;
        }

        // initialize and persist the EAV catalog attribute
        $this->persistCatalogAttribute($this->initializeAttribute($this->prepareAttributes()));
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
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     * @throws \Exception Is thrown, if the size of the option values doesn't equals the size of swatch values, in case
     */
    protected function prepareAttributes()
    {

        // load the recently created EAV attribute ID
        $attributeId = $this->getLastAttributeId();

        // initialize the attributes with the values from the DB
        $attr = $this->loadRawEntity(array(MemberNames::ATTRIBUTE_ID => $attributeId));

        // intialize the array with the column names we've to load the data from
        $columnNames = array_keys($attr);

        // iterate over the possible columns and handle the data
        foreach ($columnNames as $columnName) {
            // reverse map the table column name to the CSV column name
            $columnName = isset($this->reverseHeaderMappings[$columnName]) ? $this->reverseHeaderMappings[$columnName]
                : $columnName;
            if ($this->getSubject()->hasHeader($columnName)) {
                // query whether or not a column contains a value
                if ($this->hasValue($columnName)) {
                    $attr[$columnName] = $this->getValue($columnName);
                }
            }
        }

        // query whether or not, the column is available in the CSV file
        if ($this->getSubject()->hasHeader(ColumnKeys::ADDITIONAL_DATA)) {
            $columnName = ColumnKeys::ADDITIONAL_DATA;
            // custom handling for the additional_data column
            // load the raw additional data
            $explodedAdditionalData = $this->getValue($columnName, array(), array($this->getSubject(), 'explode'));

            // query whether or not additional data has been set
            if (sizeof($explodedAdditionalData) > 0) {
                // load and extract the additional data
                $additionalData = array();
                foreach ($explodedAdditionalData as $value) {
                    list ($key, $val) = $this->getSubject()->explode($value, '=');
                    $additionalData[$key] = $val;
                }

                // set the additional data
                $attr[$columnName] = $additionalData;

                // query whether or not the attribute is a text or a visual swatch
                if ($this->isSwatchType($additionalData)) {
                    // load the attribute option values for the custom store views
                    $attributeOptionValues =
                        $this->getValue(ColumnKeys::ATTRIBUTE_OPTION_VALUES, array(), array($this, 'explode'));
                    $attributeOptionSwatch = $this->getSubject()->explode(
                        $this->getValue(ColumnKeys::ATTRIBUTE_OPTION_SWATCH),
                        $this->getSubject()->getMultipleValueDelimiter()
                    );

                    // query whether or not the size of the option values equals the size of the swatch values
                    if (($sizeOfSwatchValues = sizeof($attributeOptionSwatch)) !== ($sizeOfOptionValues =
                            sizeof($attributeOptionValues))) {
                        throw new \Exception(
                            sprintf(
                                'Size of option values "%d" doesn\'t equals size of swatch values "%d"',
                                $sizeOfOptionValues,
                                $sizeOfSwatchValues
                            )
                        );
                    }
                }
            }
        }

        // return the prepared product
        return $this->initializeEntity($attr);
    }

    /**
     * Serialize the additional_data attribute of the passed array.
     *
     * @param array $attr The attribute with the data to serialize
     *
     * @return array The attribute with the serialized additional_data
     */
    protected function serializeAdditionalData(array $attr)
    {

        // serialize the additional data value if available
        if (isset($attr[MemberNames::ADDITIONAL_DATA]) && $attr[MemberNames::ADDITIONAL_DATA] !== null) {
            $attr[MemberNames::ADDITIONAL_DATA] = json_encode($attr[MemberNames::ADDITIONAL_DATA]);
        }

        // return the attribute
        return $attr;
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
        return $this->getAttributeBunchProcessor()->loadRawEntity(EntityTypeCodes::CATALOG_EAV_ATTRIBUTE, $data);
    }

    /**
     * Initialize the attribute with the passed attributes and returns an instance.
     *
     * @param array $attr The attribute attributes
     *
     * @return array The initialized attribute
     */
    protected function initializeAttribute(array $attr)
    {
        return $this->serializeAdditionalData($attr);
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
     * Map's the passed attribute code to the attribute ID that has been created recently.
     *
     * @param string $attributeCode The attribute code that has to be mapped
     *
     * @return void
     */
    protected function addAttributeCodeIdMapping($attributeCode)
    {
        $this->getSubject()->addAttributeCodeIdMapping($attributeCode);
    }

    /**
     * Queries whether or not the attribute with the passed code has already been processed.
     *
     * @param string $attributeCode The attribute code to check
     *
     * @return boolean TRUE if the path has been processed, else FALSE
     */
    protected function hasBeenProcessed($attributeCode)
    {
        return $this->getSubject()->hasBeenProcessed($attributeCode);
    }

    /**
     * Return's the ID of the attribute that has been created recently.
     *
     * @return integer The attribute ID
     */
    protected function getLastAttributeId()
    {
        return $this->getSubject()->getLastAttributeId();
    }

    /**
     * Return's TRUE if the additional data contains a swatch type.
     *
     * @param array $additionalData The additional data to query for a valid swatch type
     *
     * @return boolean TRUE if the data contains a swatch type, else FALSE
     */
    protected function isSwatchType(array $additionalData)
    {
        return isset($additionalData[CatalogAttributeObserver::SWATCH_INPUT_TYPE]) && in_array($additionalData[CatalogAttributeObserver::SWATCH_INPUT_TYPE], $this->swatchTypes);
    }

    /**
     * Persist the passed EAV catalog attribute.
     *
     * @param array $catalogAttribute The EAV catalog attribute to persist
     *
     * @return void
     */
    protected function persistCatalogAttribute(array $catalogAttribute)
    {
        return $this->getAttributeBunchProcessor()->persistCatalogAttribute($catalogAttribute);
    }
}
