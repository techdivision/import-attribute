<?php

/**
 * TechDivision\Import\Attribute\Observers\EntityAttributeUpdateObserver
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

use TechDivision\Import\Attribute\Utils\MemberNames;

/**
 * Observer that update's the EAV entity attribute itself.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class EntityAttributeUpdateObserver extends EntityAttributeObserver
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

        // load the attribute + attribute set ID
        $attributeId = $attr[MemberNames::ATTRIBUTE_ID];
        $attributeSetId = $attr[MemberNames::ATTRIBUTE_SET_ID];

        // try to load the entity attribute with the IDs
        if ($entityAttribute = $this->loadEntityAttributeByAttributeIdAndAttributeSetId($attributeId, $attributeSetId)) {
            return $this->mergeEntity($entityAttribute, $attr);
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Return's the EAV entity attribute with the passed attribute and attribute set ID.
     *
     * @param integer $attributeId    The ID of the EAV entity attribute's attribute to return
     * @param integer $attributeSetId The ID of the EAV entity attribute's attribute set to return
     *
     * @return array The EAV entity attribute
     */
    protected function loadEntityAttributeByAttributeIdAndAttributeSetId($attributeId, $attributeSetId)
    {
        return $this->getAttributeBunchProcessor()->loadEntityAttributeByAttributeIdAndAttributeSetId($attributeId, $attributeSetId);
    }
}
