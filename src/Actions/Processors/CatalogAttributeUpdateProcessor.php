<?php

/**
 * TechDivision\Import\Attribute\Actions\Processors\CatalogAttributeUpdateProcessor
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

namespace TechDivision\Import\Attribute\Actions\Processors;

use TechDivision\Import\Utils\EntityStatus;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\SqlStatementKeys;
use TechDivision\Import\Dbal\Collection\Actions\Processors\AbstractBaseProcessor;

/**
 * The EAV catalog attribute update processor implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class CatalogAttributeUpdateProcessor extends AbstractBaseProcessor
{

    /**
     * Return's the array with the SQL statements that has to be prepared.
     *
     * @return array The SQL statements to be prepared
     */
    protected function getStatements()
    {
        return array();
    }

    /**
     * Implements the CRUD functionality the processor is responsible for,
     * can be one of CREATE, READ, UPDATE or DELETE a entity.
     *
     * @param array       $row                  The row to persist
     * @param string|null $name                 The name of the prepared statement that has to be executed
     * @param string|null $primaryKeyMemberName The primary key member name of the entity to use
     *
     * @return string The ID of the updated entity
     */
    public function execute($row, $name = null, $primaryKeyMemberName = null)
    {

        // load the field names
        $keys = array_keys($row);

        // create a unique name for the prepared statement
        $name = sprintf('%s-%s', $name, md5(implode('-', $keys)));

        // query whether or not the statement has been prepared
        if (!$this->hasPreparedStatement($name)) {
            // remove the last value as PK from the array with the keys
            $pk = $keys[array_search(MemberNames::ATTRIBUTE_ID, $row, true)];

            // remove the entity status from the keys
            unset($keys[array_search(MemberNames::ATTRIBUTE_ID, $keys, true)]);
            unset($keys[array_search(EntityStatus::MEMBER_NAME, $keys, true)]);

            // prepare the SET part of the SQL statement
            array_walk($keys, function (&$value, $key) {
                $value = sprintf('%s=:%s', $value, $value);
            });

            // create the prepared UPDATE statement
            $statement = sprintf($this->loadStatement(SqlStatementKeys::UPDATE_CATALOG_ATTRIBUTE), implode(',', $keys), $pk);

            // prepare the statement
            $this->addPreparedStatement($name, $this->getConnection()->prepare($statement));
        }

        // pass the call to the parent method
        return parent::execute($row, $name);
    }
}
