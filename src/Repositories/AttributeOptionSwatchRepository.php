<?php

/**
 * TechDivision\Import\Attribute\Repositories\AttributeOptionSwatchRepository
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
 * Repository implementation to load EAV attribute option swatch data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionSwatchRepository extends AbstractRepository implements AttributeOptionSwatchRepositoryInterface
{

    /**
     * The prepared statement to load an existing EAV attribute option swatch by its attribute code, store ID. value and type.
     *
     * @var \PDOStatement
     */
    protected $attributeOptionSwatchByAttributeCodeAndStoreIdAndValueAndTypeStmt;

    /**
     * The prepared statement to load an existing EAV attribute option swatch by its option ID and store ID
     *
     * @var \PDOStatement
     */
    protected $attributeOptionSwatchByOptionIdAndStoreIdStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->attributeOptionSwatchByAttributeCodeAndStoreIdAndValueAndTypeStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ATTRIBUTE_OPTION_SWATCH_BY_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE_AND_TYPE));

        $this->attributeOptionSwatchByOptionIdAndStoreIdStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ATTRIBUTE_OPTION_SWATCH_BY_OPTION_ID_AND_STORE_ID));
    }

    /**
     * Load's and return's the EAV attribute option swatch with the passed code, store ID, value and type.
     *
     * @param string  $attributeCode The code of the EAV attribute option swatch to load
     * @param integer $storeId       The store ID of the attribute option swatch to load
     * @param string  $value         The value of the attribute option swatch to load
     * @param string  $type          The type of the attribute option swatch to load
     *
     * @return array The EAV attribute option swatch
     */
    public function findOneByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value, $type)
    {

        // the parameters of the EAV attribute option to load
        $params = array(
            MemberNames::ATTRIBUTE_CODE => $attributeCode,
            MemberNames::STORE_ID       => $storeId,
            MemberNames::VALUE          => $value,
            MemberNames::TYPE           => $type
        );

        // load and return the EAV attribute option swatch with the passed parameters
        $this->attributeOptionSwatchByAttributeCodeAndStoreIdAndValueAndTypeStmt->execute($params);
        return $this->attributeOptionSwatchByAttributeCodeAndStoreIdAndValueAndTypeStmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Load's and return's the EAV attribute option swatch with the passed option ID and store ID
     *
     * @param string  $optionId The option ID of the attribute option swatch to load
     * @param integer $storeId  The store ID of the attribute option swatch to load
     *
     * @return array The EAV attribute option swatch
     */
    public function findOneByOptionIdAndStoreId($optionId, $storeId)
    {

        // the parameters of the EAV attribute option to load
        $params = array(
            MemberNames::OPTION_ID => $optionId,
            MemberNames::STORE_ID  => $storeId,
        );

        // load and return the EAV attribute option swatch with the passed parameters
        $this->attributeOptionSwatchByOptionIdAndStoreIdStmt->execute($params);
        return $this->attributeOptionSwatchByOptionIdAndStoreIdStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
