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

use TechDivision\Import\Attribute\Utils\SqlStatements;
use TechDivision\Import\Actions\Processors\AbstractUpdateProcessor;
use TechDivision\Import\Utils\EntityStatus;
use TechDivision\Import\Attribute\Utils\MemberNames;

/**
 * The EAV catalog attribute update processor implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class CatalogAttributeUpdateProcessor extends AbstractUpdateProcessor
{

    /**
     * Return's the array with the SQL statements that has to be prepared.
     *
     * @return array The SQL statements to be prepared
     * @see \TechDivision\Import\Actions\Processors\AbstractBaseProcessor::getStatements()
     */
    protected function getStatements()
    {
        return array();
    }

    /**
     * Implements the CRUD functionality the processor is responsible for,
     * can be one of CREATE, READ, UPDATE or DELETE a entity.
     *
     * @param array       $row  The data to handle
     * @param string|null $name The name of the prepared statement to execute
     *
     * @return void
     */
    public function execute($row, $name = null)
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
            $statement = sprintf($this->getUtilityClass()->find(SqlStatements::UPDATE_CATALOG_ATTRIBUTE), implode(',', $keys), $pk);

            error_log($statement);

            // prepare the statement
            $this->addPreparedStatement($name, $this->getConnection()->prepare($statement));
        }

        error_log(print_r($row, true));

        // pass the call to the parent method
        return parent::execute($row, $name);
    }
}
