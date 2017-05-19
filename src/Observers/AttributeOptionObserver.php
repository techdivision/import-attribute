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

use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * Observer that create's the attribute options found in the additional CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionObserver extends AbstractAttributeImportObserver
{

    /**
     * The attribute processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

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
        if ($this->hasBeenProcessed($this->getValue(ColumnKeys::ATTRIBUTE_CODE), $this->getValue(ColumnKeys::VALUE))) {
            return;
        }

        // prepare the store view code
        $this->prepareStoreViewCode();

        // prepare the attribue values
        $attributeOption = $this->initializeAttribute($this->prepareAttributes());

        // insert the attribute option and set the option ID
        $this->setLastOptionId($this->persistAttributeOption($attributeOption));
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the attribute ID
        $attribute = $this->loadAttributeByAttributeCode($this->getValue(ColumnKeys::ATTRIBUTE_CODE));
        $attributeId = $attribute[MemberNames::ATTRIBUTE_ID];

        // load the sort order
        $sortOrder = $this->getValue(ColumnKeys::SORT_ORDER);

        // return the prepared attribute option
        return $this->initializeEntity(
            array(
                MemberNames::ATTRIBUTE_ID  => $attributeId,
                MemberNames::SORT_ORDER    => $sortOrder
            )
        );
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
     * Load's and return's the EAV attribute with the passed code.
     *
     * @param string $attributeCode The code of the EAV attribute to load
     *
     * @return array The EAV attribute
     */
    protected function loadAttributeByAttributeCode($attributeCode)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeByAttributeCode($attributeCode);
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
