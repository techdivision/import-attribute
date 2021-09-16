<?php

/**
 * TechDivision\Import\Attribute\Repositories\AttributeLabelRepository
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
 * Repository implementation to load EAV attribute label data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeLabelRepository extends AbstractRepository implements AttributeLabelRepositoryInterface
{

    /**
     * The prepared statement to load an existing EAV attribute label by its entity type ID, attribute code and store ID.
     *
     * @var \PDOStatement
     */
    protected $attributeLabelByEntityTypeIdAndAttributeCodeAndStoreIdStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->attributeLabelByEntityTypeIdAndAttributeCodeAndStoreIdStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ATTRIBUTE_LABEL_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID));
    }

    /**
     * Return's the EAV attribute label with the passed attribute code and store ID.
     *
     * @param integer $entityTypeId  The ID of the EAV entity attribute to return the label for
     * @param string  $attributeCode The attribute code of the EAV attribute label to return
     * @param integer $storeId       The store ID of the EAV attribute label to return
     *
     * @return array The EAV attribute label
     */
    public function findOneByEntityTypeIdAndAttributeCodeAndStoreId($entityTypeId, $attributeCode, $storeId)
    {

        // initialize the params
        $params = array(
            MemberNames::ENTITY_TYPE_ID => $entityTypeId,
            MemberNames::ATTRIBUTE_CODE => $attributeCode,
            MemberNames::STORE_ID       => $storeId
        );

        // load and return the EAV attribute label with the passed params
        $this->attributeLabelByEntityTypeIdAndAttributeCodeAndStoreIdStmt->execute($params);
        return $this->attributeLabelByEntityTypeIdAndAttributeCodeAndStoreIdStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
