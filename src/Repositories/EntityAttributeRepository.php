<?php

/**
 * TechDivision\Import\Attribute\Repositories\EntityAttributeRepository
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

namespace TechDivision\Import\Attribute\Repositories;

use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\SqlStatementKeys;
use TechDivision\Import\Dbal\Collection\Repositories\AbstractRepository;

/**
 * Repository implementation to load EAV entity attribute data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class EntityAttributeRepository extends AbstractRepository implements EntityAttributeRepositoryInterface
{

    /**
     * The prepared statement to load an existing EAV entity attribute by its attribut and attribut set ID.
     *
     * @var \PDOStatement
     */
    protected $entityAttributeByAttributeIdAndAttributeSetIdStmt;

    /**
     * The prepared statement to load an existing EAV entity attribute by its entity type, attribute, attribut set and attribute group ID.
     *
     * @var \PDOStatement
     */
    protected $entityAttributeByEntityTypeIdAndAttributeIdAndAttributeSetIdAndAttributeGroupIdStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->entityAttributeByAttributeIdAndAttributeSetIdStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ENTITY_ATTRIBUTE_BY_ATTRIBUTE_ID_AND_ATTRIBUTE_SET_ID));
        $this->entityAttributeByEntityTypeIdAndAttributeIdAndAttributeSetIdAndAttributeGroupIdStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ENTITY_ATTRIBUTE_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_ID_AND_ATTRIBUTE_SET_ID_AND_ATTRIBUTE_GROUP_ID));
    }

    /**
     * Return's the EAV entity attribute with the passed attribute + attribute set ID.
     *
     * @param integer $attributeId    The ID of the EAV entity attribute's attribute to return
     * @param integer $attributeSetId The ID of the EAV entity attribute's attribute set to return
     *
     * @return array The EAV entity attribute
     */
    public function findOneByAttributeIdAndAttributeSetId($attributeId, $attributeSetId)
    {

        // initialize the params
        $params = array(
            MemberNames::ATTRIBUTE_ID       => $attributeId,
            MemberNames::ATTRIBUTE_SET_ID   => $attributeSetId
        );

        // load and return the EAV entity attribute with the passed params
        $this->entityAttributeByAttributeIdAndAttributeSetIdStmt->execute($params);
        return $this->entityAttributeByAttributeIdAndAttributeSetIdStmt->fetch(\PDO::FETCH_ASSOC);
    }

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
    public function findOneByEntityTypeIdAndAttributeIdAndAttributeSetIdAndAttributeGroupId($entityTypeId, $attributeId, $attributeSetId, $attributeGroupId)
    {

        // initialize the params
        $params = array(
            MemberNames::ENTITY_TYPE_ID     => $entityTypeId,
            MemberNames::ATTRIBUTE_ID       => $attributeId,
            MemberNames::ATTRIBUTE_SET_ID   => $attributeSetId,
            MemberNames::ATTRIBUTE_GROUP_ID => $attributeGroupId
        );

        // load and return the EAV entity attribute with the passed params
        $this->entityAttributeByEntityTypeIdAndAttributeIdAndAttributeSetIdAndAttributeGroupIdStmt->execute($params);
        return $this->entityAttributeByEntityTypeIdAndAttributeIdAndAttributeSetIdAndAttributeGroupIdStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
