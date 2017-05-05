<?php

/**
 * TechDivision\Import\Attribute\Repositories\AttributeOptionValueRepository
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
 * Repository implementation to load EAV attribute option data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionValueRepository extends AbstractRepository
{

    /**
     * The prepared statement to load an existing EAV attribute option value by its attribute code, store ID and value.
     *
     * @var \PDOStatement
     */
    protected $attributeOptionValueByAttributeCodeAndStoreIdAndValueStmt;

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
        $this->attributeOptionValueByAttributeCodeAndStoreIdAndValueStmt= $this->getConnection()->prepare($utilityClassName::ATTRIBUTE_OPTION_VALUE_BY_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE);
    }

    /**
     * Load's and return's the EAV attribute option value with the passed code, store ID and value.
     *
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option value
     */
    public function findOneByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value)
    {

        // the parameters of the EAV attribute option to load
        $params = array(
            MemberNames::ATTRIBUTE_CODE => $attributeCode,
            MemberNames::STORE_ID       => $storeId,
            MemberNames::VALUE          => $value
        );

        // load and return the EAV attribute option value with the passed parameters
        $this->attributeOptionValueByAttributeCodeAndStoreIdAndValueStmt->execute($params);
        return $this->attributeOptionValueByAttributeCodeAndStoreIdAndValueStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
