<?php

/**
 * TechDivision\Import\Attribute\Repositories\EavEntityTypeRepository
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
 * @copyright 2018 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Repositories;

use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\SqlStatementKeys;

/**
 * Repository implementation to load EAV entity type data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2018 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class EavEntityTypeRepository extends \TechDivision\Import\Repositories\EavEntityTypeRepository implements EavEntityTypeRepositoryInterface
{

    /**
     * The prepared statement to load an existing EAV entity type by its entity type code.
     *
     * @var \PDOStatement
     */
    protected $entityTypeByEntityTypeACodeStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the parent instance
        parent::init();

        // initialize the prepared statements
        $this->entityTypeByEntityTypeACodeStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::EAV_ENTITY_TYPE_BY_ENTITY_TYPE_CODE));
    }

    /**
     * Return's an EAV entity type with the passed entity type code.
     *
     * @param string $entityTypeCode The code of the entity type to return
     *
     * @return array The entity type with the passed entity type code
     */
    public function findOneByEntityTypeCode($entityTypeCode)
    {
        // load and return the EAV attribute with the passed params
        $this->entityTypeByEntityTypeACodeStmt->execute(array(MemberNames::ENTITY_TYPE_CODE => $entityTypeCode));
        return $this->entityTypeByEntityTypeACodeStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
