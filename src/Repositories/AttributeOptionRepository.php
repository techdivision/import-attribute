<?php

/**
 * TechDivision\Import\Attribute\Repositories\AttributeOptionRepository
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
 * Repository implementation to load EAV attribute option data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionRepository extends AbstractRepository implements AttributeOptionRepositoryInterface
{

    /**
     * The prepared statement to load an existing EAV attribute option by its attribute code, store ID and value.
     *
     * @var \PDOStatement
     */
    protected $attributeOptionByAttributeCodeAndStoreIdAndValueStmt;

    /**
     * The prepared statement to load an existing EAV attribute option by its entity type ID, attribute code, store ID and value.
     *
     * @var \PDOStatement
     */
    protected $attributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValueStmt;

    /**
     * The prepared statement to load an existing EAV attribute option by its entity type ID, attribute code, store ID and swatch.
     *
     * @var \PDOStatement
     */
    protected $attributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndSwatchAndTypeStmt;

    /**
     * The prepared statement to load the EAV attribute option with the given attribute ID and the highest sort order.
     *
     * @var \PDOStatement
     */
    protected $attributeOptionByAttributeIdOrderBySortOrderDescStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->attributeOptionByAttributeCodeAndStoreIdAndValueStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ATTRIBUTE_OPTION_BY_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE));
        $this->attributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValueStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ATTRIBUTE_OPTION_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE));
        $this->attributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndSwatchAndTypeStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ATTRIBUTE_OPTION_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID_AND_SWATCH_AND_TYPE));
        $this->attributeOptionByAttributeIdOrderBySortOrderDescStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::ATTRIBUTE_OPTION_BY_ATTRIBUTE_ID_ORDER_BY_SORT_ORDER_DESC));
    }

    /**
     * Load's and return's the EAV attribute option with the passed code, store ID and value.
     *
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option
     * @deprecated Since 2.0.2
     * @see \TechDivision\Import\Attribute\Repositories\AttributeOptionRepositoryInterface::findOneByEntityTypeIdAndAttributeCodeAndStoreIdAndValue()
     */
    public function findOneByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value)
    {

        // the parameters of the EAV attribute option to load
        $params = array(
            MemberNames::ATTRIBUTE_CODE => $attributeCode,
            MemberNames::STORE_ID       => $storeId,
            MemberNames::VALUE          => $value
        );

        // load and return the EAV attribute option with the passed parameters
        $this->attributeOptionByAttributeCodeAndStoreIdAndValueStmt->execute($params);
        return $this->attributeOptionByAttributeCodeAndStoreIdAndValueStmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Load's and return's the EAV attribute option with the passed entity type ID and code, store ID and value.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load the option for
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option
     */
    public function findOneByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value)
    {

        // the parameters of the EAV attribute option to load
        $params = array(
            MemberNames::ENTITY_TYPE_ID => $entityTypeId,
            MemberNames::ATTRIBUTE_CODE => $attributeCode,
            MemberNames::STORE_ID       => $storeId,
            MemberNames::VALUE          => $value
        );

        // load and return the EAV attribute option with the passed parameters
        $this->attributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValueStmt->execute($params);
        return $this->attributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValueStmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Load's and return's the EAV attribute option with the passed entity type ID and code, store ID, swatch and type.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load the option for
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $swatch        The swatch of the attribute option to load
     * @param string  $type          The swatch type of the attribute option to load
     *
     * @return array The EAV attribute option
     */
    public function findOneByEntityTypeIdAndAttributeCodeAndStoreIdAndSwatchAndType($entityTypeId, $attributeCode, $storeId, $swatch, $type)
    {

        // the parameters of the EAV attribute option to load
        $params = array(
            MemberNames::ENTITY_TYPE_ID => $entityTypeId,
            MemberNames::ATTRIBUTE_CODE => $attributeCode,
            MemberNames::STORE_ID       => $storeId,
            MemberNames::VALUE          => $swatch,
            MemberNames::TYPE           => $type
        );

        // load and return the EAV attribute option with the passed parameters
        $this->attributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndSwatchAndTypeStmt->execute($params);
        return $this->attributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndSwatchAndTypeStmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns the EAV attribute option of attribute with the passed ID with the highest sort order.
     *
     * @param integer $attributeId The ID of the attribute to return the EAV option with the highest sort order for
     *
     * @return array|null The EAV attribute option with the highest sort order
     */
    public function findOneByAttributeIdAndHighestSortOrder($attributeId)
    {

        // load and return the EAV attribute option with the passed parameters
        $this->attributeOptionByAttributeIdOrderBySortOrderDescStmt->execute(array(MemberNames::ATTRIBUTE_ID => $attributeId));
        return $this->attributeOptionByAttributeIdOrderBySortOrderDescStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
