<?php

/**
 * TechDivision\Import\Attribute\Repositories\CatalogAttributeRepository
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
 * Repository implementation to load EAV catalog attribute data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class CatalogAttributeRepository extends AbstractRepository implements CatalogAttributeRepositoryInterface
{

    /**
     * The prepared statement to load an existing EAV catalog attribute by its ID.
     *
     * @var \PDOStatement
     */
    protected $catalogAttributeStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->catalogAttributeStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::CATALOG_ATTRIBUTE));
    }

    /**
     * Return's the EAV catalog attribute with the passed ID.
     *
     * @param string $attributeId The ID of the EAV catalog attribute to return
     *
     * @return array The EAV catalog attribute
     */
    public function load($attributeId)
    {
        // load and return the EAV catalog attribute with the ID
        $this->catalogAttributeStmt->execute(array(MemberNames::ATTRIBUTE_ID => $attributeId));
        return $this->catalogAttributeStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
