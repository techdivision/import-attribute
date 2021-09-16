<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionValueUpdateObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @author    Vadim Justus <v.justus@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Observers;

use TechDivision\Import\Utils\StoreViewCodes;

/**
 * Observer that update's the attribute option values found in the additional CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @author    Vadim Justus <v.justus@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionValueUpdateObserver extends AttributeOptionValueObserver
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
        // initialize the data to load the EAV attribute option
        $optionId = $this->getLastOptionId();
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);

        // try to load the EAV attribute option value
        if ($attributeOptionValue = $this->loadAttributeOptionValueByOptionIdAndStoreId($optionId, $storeId)) {
            return $this->mergeEntity($attributeOptionValue, $attr);
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's and return's the EAV attribute option value with the passed option ID and store ID.
     *
     * @param string  $optionId The option ID
     * @param integer $storeId  The store ID of the attribute option to load
     *
     * @return array The EAV attribute option value
     */
    protected function loadAttributeOptionValueByOptionIdAndStoreId($optionId, $storeId)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeOptionValueByOptionIdAndStoreId($optionId, $storeId);
    }
}
