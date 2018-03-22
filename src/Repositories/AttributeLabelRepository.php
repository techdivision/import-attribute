<?php

/**
 * TechDivision\Import\Attribute\Repositories\AttributeLabelRepository
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
use TechDivision\Import\Repositories\AbstractRepository;

/**
 * Repository implementation to load EAV attribute label data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeLabelRepository extends AbstractRepository implements AttributeLabelRepositoryInterface
{

    /**
     * The prepared statement to load an existing EAV attribute label by its attribute code and store ID.
     *
     * @var \PDOStatement
     */
    protected $attributeLabelByAttributeCodeAndStoreIdStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->attributeLabelByAttributeCodeAndStoreIdStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ATTRIBUTE_LABEL_BY_ATTRIBUTE_CODE_AND_STORE_ID));
    }

    /**
     * Return's the EAV attribute label with the passed attribute code and store ID.
     *
     * @param string  $attributeCode The attribute code of the EAV attribute label to return
     * @param integer $storeId       The store ID of the EAV attribute label to return
     *
     * @return array The EAV attribute label
     */
    public function findOneByAttributeCodeAndStoreId($attributeCode, $storeId)
    {

        // initialize the params
        $params = array(
            MemberNames::ATTRIBUTE_CODE => $attributeCode,
            MemberNames::STORE_ID       => $storeId
        );

        // load and return the EAV attribute label with the passed params
        $this->attributeLabelByAttributeCodeAndStoreIdStmt->execute($params);
        return $this->attributeLabelByAttributeCodeAndStoreIdStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
