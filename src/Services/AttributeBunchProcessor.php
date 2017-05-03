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

namespace TechDivision\Import\Attribute\Services;

use TechDivision\Import\Attribute\Actions\AttributeAction;
use TechDivision\Import\Attribute\Actions\AttributeOptionAction;
use TechDivision\Import\Attribute\Actions\AttributeOptionValueAction;
use TechDivision\Import\Attribute\Actions\CatalogAttributeAction;
use TechDivision\Import\Attribute\Repositories\AttributeRepository;
use TechDivision\Import\Attribute\Repositories\CatalogAttributeRepository;

/**
 * The attribute bunch processor implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeBunchProcessor implements AttributeBunchProcessorInterface
{

    /**
     * A PDO connection initialized with the values from the Doctrine EntityManager.
     *
     * @var \PDO
     */
    protected $connection;

    /**
     * The attribute repository instance.
     *
     * @var \TechDivision\Import\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * The catalog attribute repository instance.
     *
     * @var \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepository
     */
    protected $catalogAttributeRepository;

    /**
     * The attribute action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\AttributeAction
     */
    protected $attributeAction;

    /**
     * The attribute option action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\AttributeOptionAction
     */
    protected $attributeOptionAction;

    /**
     * The attribute option action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\AttributeOptionValueAction
     */
    protected $attributeOptionValueAction;

    /**
     * The attribute action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\CatalogAttributeAction
     */
    protected $catalogAttributeAction;

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     *
     * @param \PDO                                                                   $connection                 The PDO connection to use
     * @param \TechDivision\Import\Attribute\Repositories\AttributeRepository        $attributeRepository        The attribute repository instance
     * @param \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepository $catalogAttributeRepository The catalog attribute repository instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeAction                 $attributeAction            The attribute action instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionAction           $attributeOptionAction      The attribute option action instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionValueAction      $attributeOptionValueAction The attribute option value action instance
     * @param \TechDivision\Import\Attribute\Actions\CatalogAttributeAction          $catalogAttributeAction     The catalog attribute action instance
     */
    public function __construct(
        \PDO $connection,
        AttributeRepository $attributeRepository,
        CatalogAttributeRepository $catalogAttributeRepository,
        AttributeAction $attributeAction,
        AttributeOptionAction $attributeOptionAction,
        AttributeOptionValueAction $attributeOptionValueAction,
        CatalogAttributeAction $catalogAttributeAction
    ) {
        $this->setConnection($connection);
        $this->setAttributeRepository($attributeRepository);
        $this->setCatalogAttributeRepository($catalogAttributeRepository);
        $this->setAttributeAction($attributeAction);
        $this->setAttributeOptionAction($attributeOptionAction);
        $this->setAttributeOptionValueAction($attributeOptionValueAction);
        $this->setCatalogAttributeAction($catalogAttributeAction);
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

    /**
     * Set's the attribute repository instance.
     *
     * @param \TechDivision\Import\Attribute\Repositories\AttributeRepository $attributeRepository The attribute repository instance
     *
     * @return void
     */
    public function setAttributeRepository(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Return's the attribute repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\AttributeRepository The attribute repository instance
     */
    public function getAttributeRepository()
    {
        return $this->attributeRepository;
    }

    /**
     * Set's the catalog attribute repository instance.
     *
     * @param \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepository $catalogAttributeRepository The catalog attribute repository instance
     *
     * @return void
     */
    public function setCatalogAttributeRepository(CatalogAttributeRepository $catalogAttributeRepository)
    {
        $this->catalogAttributeRepository = $catalogAttributeRepository;
    }

    /**
     * Return's the catalog attribute repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepository The catalog attribute repository instance
     */
    public function getCatalogAttributeRepository()
    {
        return $this->catalogAttributeRepository;
    }

    /**
     * Set's the attribute action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\AttributeAction $attributeAction The attribute action instance
     *
     * @return void
     */
    public function setAttributeAction(AttributeAction $attributeAction)
    {
        $this->attributeAction = $attributeAction;
    }

    /**
     * Return's the attribute action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeAction The attribute action instance
     */
    public function getAttributeAction()
    {
        return $this->attributeAction;
    }

    /**
     * Set's the attribute option action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionAction $attributeOptionAction The attribute option action instance
     *
     * @return void
     */
    public function setAttributeOptionAction(AttributeOptionAction $attributeOptionAction)
    {
        $this->attributeOptionAction = $attributeOptionAction;
    }

    /**
     * Return's the attribute option action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeOptionAction The attribute option action instance
     */
    public function getAttributeOptionAction()
    {
        return $this->attributeOptionAction;
    }

    /**
     * Set's the attribute option value action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionValueAction $attributeOptionValueAction The attribute option value action instance
     *
     * @return void
     */
    public function setAttributeOptionValueAction(AttributeOptionValueAction $attributeOptionValueAction)
    {
        $this->attributeOptionValueAction = $attributeOptionValueAction;
    }

    /**
     * Return's the attribute option value action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeOptionValueAction The attribute option value action instance
     */
    public function getAttributeOptionValueAction()
    {
        return $this->attributeOptionValueAction;
    }

    /**
     * Set's the catalog attribute action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\CatalogAttributeAction $catalogAttributeAction The catalog attribute action instance
     *
     * @return void
     */
    public function setCatalogAttributeAction(CatalogAttributeAction $catalogAttributeAction)
    {
        $this->catalogAttributeAction = $catalogAttributeAction;
    }

    /**
     * Return's the catalog attribute action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\CatalogAttributeAction The catalog attribute action instance
     */
    public function getCatalogAttributeAction()
    {
        return $this->catalogAttributeAction;
    }

    /**
     * Load's and return's the EAV attribute with the passed code.
     *
     * @param string $attributeCode The code of the EAV attribute to load
     *
     * @return array The EAV attribute
     */
    public function loadAttributeByAttributeCode($attributeCode)
    {
        return $this->getAttributeRepository()->findOneByAttributeCode($attributeCode);
    }

    /**
     * Load's and retur's the EAV catalog attribute with the passed ID.
     *
     * @param string $attributeId The ID of the EAV catalog attribute to return
     *
     * @return array The EAV catalog attribute
     */
    public function loadCatalogAttribute($attributeId)
    {
        return $this->getCatalogAttributeRepository()->load($attributeId);
    }

    /**
     * Persist's the passed EAV attribute data and return's the ID.
     *
     * @param array       $attribute The attribute data to persist
     * @param string|null $name      The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted attribute
     */
    public function persistAttribute(array $attribute, $name = null)
    {
        return $this->getAttributeAction()->persist($attribute, $name);
    }

    /**
     * Persist's the passed EAV attribute option data and return's the ID.
     *
     * @param array       $attributeOption The attribute option data to persist
     * @param string|null $name            The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted attribute
     */
    public function persistAttributeOption(array $attributeOption, $name = null)
    {
        return $this->getAttributeOptionAction()->persist($attributeOption, $name);
    }

    /**
     * Persist's the passed EAV attribute option value data and return's the ID.
     *
     * @param array       $attributeOptionValue The attribute option value data to persist
     * @param string|null $name                 The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistAttributeOptionValue(array $attributeOptionValue, $name = null)
    {
        $this->getAttributeOptionValueAction()->persist($attributeOptionValue, $name);
    }

    /**
     * Persist's the passed EAV catalog attribute data and return's the ID.
     *
     * @param array       $catalogAttribute The catalog attribute data to persist
     * @param string|null $name             The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistCatalogAttribute(array $catalogAttribute, $name = null)
    {
        $this->getCatalogAttributeAction()->persist($catalogAttribute, $name);
    }

    /**
     * Delete's the EAV attribute with the passed attributes.
     *
     * @param array       $row  The attributes of the EAV attribute to delete
     * @param string|null $name The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function deleteAttribute($row, $name = null)
    {
        $this->getAttributeAction()->delete($row, $name);
    }
}
