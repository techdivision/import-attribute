<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionUpdateObserver
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

/**
 * Observer that update's the attribute options found in the additional CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionUpdateObserver extends AttributeOptionObserver
{

    /**
     * Merge's and return's the entity with the passed attributes and set's the
     * passed status.
     *
     * @param array       $entity        The entity to merge the attributes into
     * @param array       $attr          The attributes to be merged
     * @param string|null $changeSetName The change set name to use
     *
     * @return array The merged entity
     */
    protected function mergeEntity(array $entity, array $attr, $changeSetName = null)
    {

        // query whether or not the sort order has been specified, if not use the value of the
        // existing entity. This allows the customer to change the order in the Magento backend
        if ($this->hasValue(ColumnKeys::SORT_ORDER) === false) {
            $attr[MemberNames::SORT_ORDER] = $entity[MemberNames::SORT_ORDER];
        }

        // invoke the parent method and return the merged entity
        return parent::mergeEntity($entity, $attr, $changeSetName);
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

        // load the entity type ID for the value from the system configuration
        $entityTypeId = $this->getEntityTypeId();

        // initialize the data to load the EAV attribute option
        $value = $this->getValue(ColumnKeys::VALUE);
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);
        $attributeCode = $this->getValue(ColumnKeys::ATTRIBUTE_CODE);

        // try to load the EAV attribute option
        if ($attributeOption = $this->loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value)) {
            return $this->mergeEntity($attributeOption, $attr);
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's and return's the EAV attribute option with the passed entity type ID, code, store ID and value.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load the option for
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option
     */
    protected function loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value);
    }
}
