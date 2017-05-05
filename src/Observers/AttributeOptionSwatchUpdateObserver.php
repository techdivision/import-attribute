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

/**
 * Observer that update's the attribute option swatchs found in the additional CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionSwatchUpdateObserver extends AttributeOptionSwatchObserver
{

    /**
     * Initialize the EAV attribute option value with the passed attributes and returns an instance.
     *
     * @param array $attr The EAV attribute option value attributes
     *
     * @return array The initialized EAV attribute option value
     */
    protected function initializeAttribute(array $attr)
    {

        // initialize the data to load the EAV attribute option swatch
        $value = $this->getValue(ColumnKeys::SWATCH_VALUE);
        $type = $this->getValue(ColumnKeys::SWATCH_TYPE);
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);
        $attributeCode = $this->getValue(ColumnKeys::ATTRIBUTE_CODE);

        // try to load the EAV attribute option swatch
        if ($attributeOptionSwatch = $this->loadAttributeOptionSwatchByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value, $type)) {
            return $this->mergeEntity($attributeOptionSwatch, $attr);
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's and return's the EAV attribute option swatch with the passed code, store ID, value and type.
     *
     * @param string  $attributeCode The code of the EAV attribute option swatch to load
     * @param integer $storeId       The store ID of the attribute option swatch to load
     * @param string  $value         The value of the attribute option swatch to load
     * @param string  $type          The type of the attribute option swatch to load
     *
     * @return array The EAV attribute option swatch
     */
    protected function loadAttributeOptionSwatchByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value, $type)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeOptionSwatchByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value, $type);
    }
}
