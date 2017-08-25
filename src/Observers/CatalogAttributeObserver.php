<?php

/**
 * TechDivision\Import\Attribute\Observers\CatalogAttributeObserver
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

use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * Observer that create's the EAV catalog attribute itself.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
    protected $swatchTypes = array('text', 'visual');

    /**
     * The attribute processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

    /**
     * The array with the possible column names.
     *
     * @var array
     */
    protected $columnNames = array(
        ColumnKeys::FRONTEND_INPUT_RENDERER,
        ColumnKeys::IS_GLOBAL,
        ColumnKeys::IS_VISIBLE,
        ColumnKeys::IS_SEARCHABLE,
        ColumnKeys::IS_FILTERABLE,
        ColumnKeys::IS_COMPARABLE,
        ColumnKeys::IS_VISIBLE_ON_FRONT,
        ColumnKeys::IS_HTML_ALLOWED_ON_FRONT,
        ColumnKeys::IS_USED_FOR_PRICE_RULES,
        ColumnKeys::IS_FILTERABLE_IN_SEARCH,
        ColumnKeys::USED_IN_PRODUCT_LISTING,
        ColumnKeys::USED_FOR_SORT_BY,
        ColumnKeys::APPLY_TO,
        ColumnKeys::IS_VISIBLE_IN_ADVANCED_SEARCH,
        ColumnKeys::POSITION,
        ColumnKeys::IS_WYSIWYG_ENABLED,
        ColumnKeys::IS_USED_FOR_PROMO_RULES,
        ColumnKeys::IS_REQUIRED_IN_ADMIN_STORE,
        ColumnKeys::IS_USED_IN_GRID,
        ColumnKeys::IS_VISIBLE_IN_GRID,
        ColumnKeys::IS_FILTERABLE_IN_GRID,
        ColumnKeys::SEARCH_WEIGHT,
        ColumnKeys::ADDITIONAL_DATA
    );

    /**
     * Initializes the observer with the passed subject instance.
     *
     * @param \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface $attributeBunchProcessor The attribute bunch processor instance
     */
    public function __construct(AttributeBunchProcessorInterface $attributeBunchProcessor)
    {
        $this->attributeBunchProcessor = $attributeBunchProcessor;
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
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     * @throws \Exception Is thrown, if the size of the option values doesn't equals the size of swatch values, in case
     */
    protected function prepareAttributes()
    {

        // load the recently created EAV attribute ID
        $attributeId = $this->getLastAttributeId();

        // initialize the attributes
        $attr = array(MemberNames::ATTRIBUTE_ID => $attributeId);

        // iterate over the possible columns and handle the data
        foreach ($this->columnNames as $columnName) {
            // query whether or not, the column is available in the CSV file
            if ($this->getSubject()->hasHeader($columnName)) {
                // custom handling for the additional_data column
                if ($columnName === ColumnKeys::ADDITIONAL_DATA) {
                    // load the raw additional data
                    $explodedAdditionalData = $this->getValue(ColumnKeys::ADDITIONAL_DATA, array(), array($this->getSubject(), 'explode'));

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
                            $attributeOptionValues = $this->getValue(ColumnKeys::ATTRIBUTE_OPTION_VALUES, array(), array($this, 'explode'));
                            $attributeOptionSwatch = $this->getSubject()->explode($this->getValue(ColumnKeys::ATTRIBUTE_OPTION_SWATCH), $this->getSubject()->getMultipleValueDelimiter());

                            // query whether or not the size of the option values equals the size of the swatch values
                            if (($sizeOfSwatchValues = sizeof($attributeOptionSwatch)) !== ($sizeOfOptionValues = sizeof($attributeOptionValues))) {
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

                } else {
                    // query whether or not a column contains a value
                    if ($this->hasValue($columnName)) {
                        $attr[$columnName] = $this->getValue($columnName);
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
        if (isset($attr[MemberNames::ADDITIONAL_DATA]) && is_array($attr[MemberNames::ADDITIONAL_DATA])) {
            $attr[MemberNames::ADDITIONAL_DATA] = serialize($attr[MemberNames::ADDITIONAL_DATA]);
        }

        // return the attribute
        return $attr;
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
