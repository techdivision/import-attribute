<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionSwatchObserver
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

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * Observer that create's the attribute option swatchs found in the additional CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionSwatchObserver extends AbstractAttributeImportObserver
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

        // prepare the store view code
        $this->prepareStoreViewCode();

        // prepare and insert the attribute option swatch
        if ($attr = $this->prepareAttributes()) {
            $this->persistAttributeOptionSwatch($this->initializeAttribute($attr));
        }
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the option ID
        $optionId = $this->getLastOptionId();

        // load the store ID, value + type
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);
        $value = $this->getValue(ColumnKeys::SWATCH_VALUE);
        $type = $this->getValue(ColumnKeys::SWATCH_TYPE);

        // load the attribute option swatch value/type
        if ($value !== null && $type !== null) {
            // return the prepared attribute option
            return $this->initializeEntity(
                array(
                    MemberNames::OPTION_ID  => $optionId,
                    MemberNames::STORE_ID   => $storeId,
                    MemberNames::VALUE      => $value,
                    MemberNames::TYPE       => $type
                )
            );
        }
    }

    /**
     * Initialize the EAV attribute option value with the passed attributes and returns an instance.
     *
     * @param array $attr The EAV attribute option value attributes
     *
     * @return array The initialized EAV attribute option value
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
     * Return's the ID of the option that has been created recently.
     *
     * @return integer The option ID
     */
    protected function getLastOptionId()
    {
        return $this->getSubject()->getLastOptionId();
    }

    /**
     * Persist the passed attribute option swatch.
     *
     * @param array $attributeOptionSwatch The attribute option swatch to persist
     *
     * @return void
     */
    protected function persistAttributeOptionSwatch(array $attributeOptionSwatch)
    {
        return $this->getAttributeBunchProcessor()->persistAttributeOptionSwatch($attributeOptionSwatch);
    }
}
