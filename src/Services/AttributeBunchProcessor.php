<?php

/**
 * TechDivision\Import\Attribute\Services\AttributeBunchProcessor
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

namespace TechDivision\Import\Category\Services;

/**
 * The attribute bunch processor implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeBunchProcessor implements CategoryBunchProcessorInterface
{

    /**
     * A PDO connection initialized with the values from the Doctrine EntityManager.
     *
     * @var \PDO
     */
    protected $connection;

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     */
    public function __construct(
        \PDO $connection
    ) {
        $this->setConnection($connection);
    }

    /**
     * Set's the passed connection.
     *
     * @param \PDO $connection The connection to set
     *
     * @return void
     */
    public function setConnection(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Return's the connection.
     *
     * @return \PDO The connection instance
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Turns off autocommit mode. While autocommit mode is turned off, changes made to the database via the PDO
     * object instance are not committed until you end the transaction by calling ProductProcessor::commit().
     * Calling ProductProcessor::rollBack() will roll back all changes to the database and return the connection
     * to autocommit mode.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.begintransaction.php
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commits a transaction, returning the database connection to autocommit mode until the next call to
     * ProductProcessor::beginTransaction() starts a new transaction.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.commit.php
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Rolls back the current transaction, as initiated by ProductProcessor::beginTransaction().
     *
     * If the database was set to autocommit mode, this function will restore autocommit mode after it has
     * rolled back the transaction.
     *
     * Some databases, including MySQL, automatically issue an implicit COMMIT when a database definition
     * language (DDL) statement such as DROP TABLE or CREATE TABLE is issued within a transaction. The implicit
     * COMMIT will prevent you from rolling back any other changes within the transaction boundary.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.rollback.php
     */
    public function rollBack()
    {
        return $this->connection->rollBack();
    }
}
