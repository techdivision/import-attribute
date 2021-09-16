<?php

/**
 * TechDivision\Import\Attribute\Repositories\EntityAttributeRepositoryInterface
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Repositories;

use TechDivision\Import\Repositories\RepositoryInterface;

/**
 * Interface for repository implementations to load EAV entity attribute data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
interface EntityAttributeRepositoryInterface extends RepositoryInterface
{

    /**
     * Return's the EAV entity attribute with the passed attribute + attribute set ID.
     *
     * @param integer $attributeId    The ID of the EAV entity attribute's attribute to return
     * @param integer $attributeSetId The ID of the EAV entity attribute's attribute set to return
     *
     * @return array The EAV entity attribute
     */
    public function findOneByAttributeIdAndAttributeSetId($attributeId, $attributeSetId);

    /**
     * Return's the EAV entity attribute with the passed entity type, attribute, attribute set and attribute group ID.
     *
     * @param integer $entityTypeId     The ID of the EAV entity attribute's entity type to return
     * @param integer $attributeId      The ID of the EAV entity attribute's attribute to return
     * @param integer $attributeSetId   The ID of the EAV entity attribute's attribute set to return
     * @param integer $attributeGroupId The ID of the EAV entity attribute's attribute group to return
     *
     * @return array The EAV entity attribute
     */
    public function findOneByEntityTypeIdAndAttributeIdAndAttributeSetIdAndAttributeGroupId($entityTypeId, $attributeId, $attributeSetId, $attributeGroupId);
}
