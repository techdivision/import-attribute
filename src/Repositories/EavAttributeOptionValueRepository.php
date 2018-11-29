<?php

/**
 * TechDivision\Import\Repositories\EavAttributeOptionValueCachedRepository
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
 * @link      https://github.com/techdivision/import
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Repositories;

use TechDivision\Import\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\SqlStatementKeys;

/**
 * Cached repository implementation to load EAV attribute option value data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2018 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import
 * @link      http://www.techdivision.com
 */
class EavAttributeOptionValueRepository extends \TechDivision\Import\Repositories\EavAttributeOptionValueRepository implements EavAttributeOptionValueRepositoryInterface
{

    /**
     * The prepared statement to load an existing EAV attribute option value by its entity type ID, attribute code, store ID and value.
     *
     * @var \PDOStatement
     */
    protected $eavAttributeOptionValueByEntityTypeIdAndAttributeCodeAndStoreIdAndValueStmt;

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
        $this->eavAttributeOptionValueByEntityTypeIdAndAttributeCodeAndStoreIdAndValueStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::EAV_ATTRIBUTE_OPTION_VALUE_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE));
    }

    /**
     * Load's and return's the EAV attribute option value with the passed entity type ID, code, store ID and value.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load the option value for
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option value
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

        // prepare the cache key
        $cacheKey = $this->cacheKey(SqlStatementKeys::EAV_ATTRIBUTE_OPTION_VALUE_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE, $params);

        // return the cached result if available
        if ($this->isCached($cacheKey)) {
            return $this->fromCache($cacheKey);
        }

        // load and return the EAV attribute option value with the passed parameters
        $this->eavAttributeOptionValueByEntityTypeIdAndAttributeCodeAndStoreIdAndValueStmt->execute($params);

        // query whether or not the result has been cached
        if ($eavAttributeOptionValue = $this->eavAttributeOptionValueByEntityTypeIdAndAttributeCodeAndStoreIdAndValueStmt->fetch(\PDO::FETCH_ASSOC)) {
            // add the EAV attribute option value to the cache, register the cache key reference as well
            $this->toCache(
                $eavAttributeOptionValue[MemberNames::VALUE_ID],
                $eavAttributeOptionValue,
                array($cacheKey => $eavAttributeOptionValue[MemberNames::VALUE_ID])
            );
            // finally, return it
            return $eavAttributeOptionValue;
        }
    }
}
