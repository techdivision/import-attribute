<?php

/**
 * TechDivision\Import\Attribute\Repositories\AttributeRepository
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
use TechDivision\Import\Repositories\AbstractRepository;

/**
 * Repository implementation to load EAV attribute data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeRepository extends AbstractRepository
{

    /**
     * The prepared statement to load an existing EAV attribute by its attribute code.
     *
     * @var \PDOStatement
     */
    protected $attributeByAttributeCodeStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // load the utility class name
        $utilityClassName = $this->getUtilityClassName();

        // initialize the prepared statements
        $this->attributeByAttributeCodeStmt =
            $this->getConnection()->prepare($this->getUtilityClass()->find($utilityClassName::ATTRIBUTE_BY_ATTRIBUTE_CODE));
    }

    /**
     * Return's the EAV attribute with the passed code.
     *
     * @param string $attributeCode The code of the EAV attribute to return
     *
     * @return array The EAV attribute
     */
    public function findOneByAttributeCode($attributeCode)
    {
        // load and return the EAV attribute with the passed code
        $this->attributeByAttributeCodeStmt->execute(array(MemberNames::ATTRIBUTE_CODE => $attributeCode));
        return $this->attributeByAttributeCodeStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
