<?php

/**
 * TechDivision\Import\Attribute\Observers\CatalogAttributeUpdateObserver
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

use TechDivision\Import\Attribute\Utils\MemberNames;

/**
 * Observer that add/update's the EAV catalog attribute itself.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class CatalogAttributeUpdateObserver extends CatalogAttributeObserver
{

    /**
     * Initialize the attribute with the passed attributes and returns an instance.
     *
     * @param array $attr The attribute attributes
     *
     * @return array The initialized attribute
     */
    protected function initializeAttribute(array $attr)
    {

        // try to load the EAV catalog attribute with the attribute code
        if ($attribute = $this->loadCatalogAttribute($attr[MemberNames::ATTRIBUTE_ID])) {
            // unserialize the additional data value if available
            if (is_array($attribute[MemberNames::ADDITIONAL_DATA]) && $attribute[MemberNames::ADDITIONAL_DATA] !== null) {
                // merge the additional data if available
                $attribute[MemberNames::ADDITIONAL_DATA] = array_merge(
                    $attr[MemberNames::ADDITIONAL_DATA],
                    unserialize($attribute[MemberNames::ADDITIONAL_DATA])
                );

            } elseif (!is_array($attribute[MemberNames::ADDITIONAL_DATA]) && $attribute[MemberNames::ADDITIONAL_DATA] !== null) {
                // unserialize and override the additional data
                $attribute[MemberNames::ADDITIONAL_DATA] = unserialize($attribute[MemberNames::ADDITIONAL_DATA]);
            } else {
                // nothing here
            }

            // merge the attributes into the entity
            return $this->serializeAdditionalData($this->mergeEntity($attribute, $attr));
        }

        //  serialize the additional data and return the attributes
        return parent::initializeAttribute($attr);
    }

    /**
     * Load's and return's the EAV catalog attribute with the passed attribute ID.
     *
     * @param string $attributeId The ID of the EAV catalog attribute to load
     *
     * @return array The EAV catalog attribute
     */
    protected function loadCatalogAttribute($attributeId)
    {
        return $this->getAttributeBunchProcessor()->loadCatalogAttribute($attributeId);
    }
}
