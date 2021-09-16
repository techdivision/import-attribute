<?php

/**
 * TechDivision\Import\Attribute\Repositories\AttributeRepository
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

use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\SqlStatementKeys;
use TechDivision\Import\Dbal\Collection\Repositories\AbstractRepository;

/**
 * Repository implementation to load EAV attribute data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeRepository extends AbstractRepository implements AttributeRepositoryInterface
{

    /**
     * The prepared statement to load an existing EAV attribute by its entity type ID and attribute code.
     *
     * @var \PDOStatement
     */
    protected $attributeByEntityTypeIdAndAttributeCodeStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->attributeByEntityTypeAndAttributeCodeStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ATTRIBUTE_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE));
    }

    /**
     * Return's the EAV attribute with the passed entity type ID and code.
     *
     * @param integer $entityTypeId  The entity type ID of the EAV attribute to return
     * @param string  $attributeCode The code of the EAV attribute to return
     *
     * @return array The EAV attribute
     */
    public function findOneByEntityIdAndAttributeCode($entityTypeId, $attributeCode)
    {

        // initialize the params
        $params = array(
            MemberNames::ENTITY_TYPE_ID => $entityTypeId,
            MemberNames::ATTRIBUTE_CODE => $attributeCode
        );

        // load and return the EAV attribute with the passed params
        $this->attributeByEntityTypeAndAttributeCodeStmt->execute($params);
        return $this->attributeByEntityTypeAndAttributeCodeStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
