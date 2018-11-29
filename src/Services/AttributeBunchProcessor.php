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

use TechDivision\Import\Connection\ConnectionInterface;
use TechDivision\Import\Repositories\EavAttributeOptionValueRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\AttributeRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\AttributeLabelRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\AttributeOptionRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\EntityAttributeRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\CatalogAttributeRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\AttributeOptionSwatchRepositoryInterface;
use TechDivision\Import\Attribute\Actions\AttributeActionInterface;
use TechDivision\Import\Attribute\Actions\AttributeLabelActionInterface;
use TechDivision\Import\Attribute\Actions\AttributeOptionActionInterface;
use TechDivision\Import\Attribute\Actions\EntityAttributeActionInterface;
use TechDivision\Import\Attribute\Actions\CatalogAttributeActionInterface;
use TechDivision\Import\Attribute\Actions\AttributeOptionValueActionInterface;
use TechDivision\Import\Attribute\Actions\AttributeOptionSwatchActionInterface;

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
     * A connection to use.
     *
     * @var \TechDivision\Import\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * The attribute repository instance.
     *
     * @var \TechDivision\Import\Attribute\Repositories\AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * The attribute label repository instance.
     *
     * @var \TechDivision\Import\Attribute\Repositories\AttributeLabelRepositoryInterface
     */
    protected $attributeLabelRepository;

    /**
     * The attribute option repository instance.
     *
     * @var \TechDivision\Import\Attribute\Repositories\AttributeOptionRepositoryInterface
     */
    protected $attributeOptionRepository;

    /**
     * The repository to access EAV attribute option values.
     *
     * @var \TechDivision\Import\Repositories\EavAttributeOptionValueRepositoryInterface
     */
    protected $eavAttributeOptionValueRepository;

    /**
     * The attribute option swatch repository instance.
     *
     * @var \TechDivision\Import\Attribute\Repositories\AttributeOptionSwatchRepositoryInterface
     */
    protected $attributeOptionSwatchRepository;

    /**
     * The catalog attribute repository instance.
     *
     * @var \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepositoryInterface
     */
    protected $catalogAttributeRepository;

    /**
     * The entity attribute repository instance.
     *
     * @var \TechDivision\Import\Attribute\Repositories\EntityAttributeRepositoryInterface
     */
    protected $entityAttributeRepository;

    /**
     * The attribute action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\AttributeActionInterface
     */
    protected $attributeAction;

    /**
     * The attribute label action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\AttributeLabelActionInterface
     */
    protected $attributeLabelAction;

    /**
     * The attribute option action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\AttributeOptionActionInterface
     */
    protected $attributeOptionAction;

    /**
     * The attribute option value action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\AttributeOptionValueActionInterface
     */
    protected $attributeOptionValueAction;

    /**
     * The attribute option swatch action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\AttributeOptionSwatchActionInterface
     */
    protected $attributeOptionSwatchAction;

    /**
     * The attribute action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\CatalogAttributeActionInterface
     */
    protected $catalogAttributeAction;

    /**
     * The entity attribute action instance.
     *
     * @var \TechDivision\Import\Attribute\Actions\EntityAttributeActionInterface
     */
    protected $entityAttributeAction;

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     *
     * @param \TechDivision\Import\Connection\ConnectionInterface                                  $connection                        The connection to use
     * @param \TechDivision\Import\Attribute\Repositories\AttributeRepositoryInterface             $attributeRepository               The attribute repository instance
     * @param \TechDivision\Import\Attribute\Repositories\AttributeLabelRepositoryInterface        $attributeLabelRepository          The attribute label repository instance
     * @param \TechDivision\Import\Attribute\Repositories\AttributeOptionRepositoryInterface       $attributeOptionRepository         The attribute repository instance
     * @param \TechDivision\Import\Repositories\EavAttributeOptionValueRepositoryInterface         $eavAttributeOptionValueRepository The EAV attribute option value repository to use
     * @param \TechDivision\Import\Attribute\Repositories\AttributeOptionSwatchRepositoryInterface $attributeOptionSwatchRepository   The attribute repository swatch instance
     * @param \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepositoryInterface      $catalogAttributeRepository        The catalog attribute repository instance
     * @param \TechDivision\Import\Attribute\Repositories\EntityAttributeRepositoryInterface       $entityAttributeRepository         The entity attribute repository instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeActionInterface                      $attributeAction                   The attribute action instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeLabelActionInterface                 $attributeLabelAction              The attribute label action instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionActionInterface                $attributeOptionAction             The attribute option action instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionValueActionInterface           $attributeOptionValueAction        The attribute option value action instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionSwatchActionInterface          $attributeOptionSwatchAction       The attribute option swatch action instance
     * @param \TechDivision\Import\Attribute\Actions\CatalogAttributeActionInterface               $catalogAttributeAction            The catalog attribute action instance
     * @param \TechDivision\Import\Attribute\Actions\EntityAttributeActionInterface                $entityAttributeAction             The entity attribute action instance
     */
    public function __construct(
        ConnectionInterface $connection,
        AttributeRepositoryInterface $attributeRepository,
        AttributeLabelRepositoryInterface $attributeLabelRepository,
        AttributeOptionRepositoryInterface $attributeOptionRepository,
        EavAttributeOptionValueRepositoryInterface $eavAttributeOptionValueRepository,
        AttributeOptionSwatchRepositoryInterface $attributeOptionSwatchRepository,
        CatalogAttributeRepositoryInterface $catalogAttributeRepository,
        EntityAttributeRepositoryInterface $entityAttributeRepository,
        AttributeActionInterface $attributeAction,
        AttributeLabelActionInterface $attributeLabelAction,
        AttributeOptionActionInterface $attributeOptionAction,
        AttributeOptionValueActionInterface $attributeOptionValueAction,
        AttributeOptionSwatchActionInterface $attributeOptionSwatchAction,
        CatalogAttributeActionInterface $catalogAttributeAction,
        EntityAttributeActionInterface $entityAttributeAction
    ) {
        $this->setConnection($connection);
        $this->setAttributeRepository($attributeRepository);
        $this->setAttributeLabelRepository($attributeLabelRepository);
        $this->setAttributeOptionRepository($attributeOptionRepository);
        $this->setEavAttributeOptionValueRepository($eavAttributeOptionValueRepository);
        $this->setAttributeOptionSwatchRepository($attributeOptionSwatchRepository);
        $this->setCatalogAttributeRepository($catalogAttributeRepository);
        $this->setEntityAttributeRepository($entityAttributeRepository);
        $this->setAttributeAction($attributeAction);
        $this->setAttributeLabelAction($attributeLabelAction);
        $this->setAttributeOptionAction($attributeOptionAction);
        $this->setAttributeOptionValueAction($attributeOptionValueAction);
        $this->setAttributeOptionSwatchAction($attributeOptionSwatchAction);
        $this->setCatalogAttributeAction($catalogAttributeAction);
        $this->setEntityAttributeAction($entityAttributeAction);
    }

    /**
     * Set's the passed connection.
     *
     * @param \TechDivision\Import\Connection\ConnectionInterface $connection The connection to set
     *
     * @return void
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Return's the connection.
     *
     * @return \TechDivision\Import\Connection\ConnectionInterface The connection instance
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
     * @param \TechDivision\Import\Attribute\Repositories\AttributeRepositoryInterface $attributeRepository The attribute repository instance
     *
     * @return void
     */
    public function setAttributeRepository(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Return's the attribute repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\AttributeRepositoryInterface The attribute repository instance
     */
    public function getAttributeRepository()
    {
        return $this->attributeRepository;
    }

    /**
     * Set's the attribute label repository instance.
     *
     * @param \TechDivision\Import\Attribute\Repositories\AttributeLabelRepositoryInterface $attributeLabelRepository The attribute label repository instance
     *
     * @return void
     */
    public function setAttributeLabelRepository(AttributeLabelRepositoryInterface $attributeLabelRepository)
    {
        $this->attributeLabelRepository = $attributeLabelRepository;
    }

    /**
     * Return's the attribute label repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\AttributeRepositoryInterface The attribute label repository instance
     */
    public function getAttributeLabelRepository()
    {
        return $this->attributeLabelRepository;
    }

    /**
     * Set's the attribute option repository instance.
     *
     * @param \TechDivision\Import\Attribute\Repositories\AttributeOptionRepositoryInterface $attributeOptionRepository The attribute option repository instance
     *
     * @return void
     */
    public function setAttributeOptionRepository(AttributeOptionRepositoryInterface $attributeOptionRepository)
    {
        $this->attributeOptionRepository = $attributeOptionRepository;
    }

    /**
     * Return's the attribute option repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\AttributeOptionRepositoryInterface The attribute option repository instance
     */
    public function getAttributeOptionRepository()
    {
        return $this->attributeOptionRepository;
    }

    /**
     * Set's the repository to access EAV attribute option values.
     *
     * @param \TechDivision\Import\Repositories\EavAttributeOptionValueRepositoryInterface $eavAttributeOptionValueRepository The repository to access EAV attribute option values
     *
     * @return void
     */
    public function setEavAttributeOptionValueRepository(EavAttributeOptionValueRepositoryInterface $eavAttributeOptionValueRepository)
    {
        $this->eavAttributeOptionValueRepository = $eavAttributeOptionValueRepository;
    }

    /**
     * Return's the repository to access EAV attribute option values.
     *
     * @return \TechDivision\Import\Repositories\EavAttributeOptionValueRepositoryInterface The repository instance
     */
    public function getEavAttributeOptionValueRepository()
    {
        return $this->eavAttributeOptionValueRepository;
    }

    /**
     * Set's the attribute option swatch repository instance.
     *
     * @param \TechDivision\Import\Attribute\Repositories\AttributeOptionSwatchRepositoryInterface $attributeOptionSwatchRepository The attribute option swatch repository instance
     *
     * @return void
     */
    public function setAttributeOptionSwatchRepository(AttributeOptionSwatchRepositoryInterface $attributeOptionSwatchRepository)
    {
        $this->attributeOptionSwatchRepository = $attributeOptionSwatchRepository;
    }

    /**
     * Return's the attribute option swatch repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\AttributeOptionSwatchRepositoryInterface The attribute option swatch repository instance
     */
    public function getAttributeOptionSwatchRepository()
    {
        return $this->attributeOptionSwatchRepository;
    }

    /**
     * Set's the catalog attribute repository instance.
     *
     * @param \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepositoryInterface $catalogAttributeRepository The catalog attribute repository instance
     *
     * @return void
     */
    public function setCatalogAttributeRepository(CatalogAttributeRepositoryInterface $catalogAttributeRepository)
    {
        $this->catalogAttributeRepository = $catalogAttributeRepository;
    }

    /**
     * Return's the catalog attribute repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepositoryInterface The catalog attribute repository instance
     */
    public function getCatalogAttributeRepository()
    {
        return $this->catalogAttributeRepository;
    }

    /**
     * Set's the entity attribute repository instance.
     *
     * @param \TechDivision\Import\Attribute\Repositories\EntityAttributeRepositoryInterface $entityAttributeRepository The entity attribute repository instance
     *
     * @return void
     */
    public function setEntityAttributeRepository(EntityAttributeRepositoryInterface $entityAttributeRepository)
    {
        $this->entityAttributeRepository = $entityAttributeRepository;
    }

    /**
     * Return's the entity attribute repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\EntityAttributeRepositoryInterface The entity attribute repository instance
     */
    public function getEntityAttributeRepository()
    {
        return $this->entityAttributeRepository;
    }

    /**
     * Set's the attribute action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\AttributeActionInterface $attributeAction The attribute action instance
     *
     * @return void
     */
    public function setAttributeAction(AttributeActionInterface $attributeAction)
    {
        $this->attributeAction = $attributeAction;
    }

    /**
     * Return's the attribute action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeActionInterface The attribute action instance
     */
    public function getAttributeAction()
    {
        return $this->attributeAction;
    }

    /**
     * Set's the attribute label action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\AttributeLabelActionInterface $attributeLabelAction The attribute label action instance
     *
     * @return void
     */
    public function setAttributeLabelAction(AttributeLabelActionInterface $attributeLabelAction)
    {
        $this->attributeLabelAction = $attributeLabelAction;
    }

    /**
     * Return's the attribute label action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeActionInterface The attribute label action instance
     */
    public function getAttributeLabelAction()
    {
        return $this->attributeLabelAction;
    }

    /**
     * Set's the attribute option action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionActionInterface $attributeOptionAction The attribute option action instance
     *
     * @return void
     */
    public function setAttributeOptionAction(AttributeOptionActionInterface $attributeOptionAction)
    {
        $this->attributeOptionAction = $attributeOptionAction;
    }

    /**
     * Return's the attribute option action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeOptionActionInterface The attribute option action instance
     */
    public function getAttributeOptionAction()
    {
        return $this->attributeOptionAction;
    }

    /**
     * Set's the attribute option value action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionValueActionInterface $attributeOptionValueAction The attribute option value action instance
     *
     * @return void
     */
    public function setAttributeOptionValueAction(AttributeOptionValueActionInterface $attributeOptionValueAction)
    {
        $this->attributeOptionValueAction = $attributeOptionValueAction;
    }

    /**
     * Return's the attribute option value action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeOptionValueActionInterface The attribute option value action instance
     */
    public function getAttributeOptionValueAction()
    {
        return $this->attributeOptionValueAction;
    }

    /**
     * Set's the attribute option swatch action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionSwatchActionInterface $attributeOptionSwatchAction The attribute option swatch action instance
     *
     * @return void
     */
    public function setAttributeOptionSwatchAction(AttributeOptionSwatchActionInterface $attributeOptionSwatchAction)
    {
        $this->attributeOptionSwatchAction = $attributeOptionSwatchAction;
    }

    /**
     * Return's the attribute option swatch action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeOptionSwatchActionInterface The attribute option swatch action instance
     */
    public function getAttributeOptionSwatchAction()
    {
        return $this->attributeOptionSwatchAction;
    }

    /**
     * Set's the catalog attribute action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\CatalogAttributeActionInterface $catalogAttributeAction The catalog attribute action instance
     *
     * @return void
     */
    public function setCatalogAttributeAction(CatalogAttributeActionInterface $catalogAttributeAction)
    {
        $this->catalogAttributeAction = $catalogAttributeAction;
    }

    /**
     * Return's the catalog attribute action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\CatalogAttributeActionInterface The catalog attribute action instance
     */
    public function getCatalogAttributeAction()
    {
        return $this->catalogAttributeAction;
    }

    /**
     * Set's the entity attribute action instance.
     *
     * @param \TechDivision\Import\Attribute\Actions\EntityAttributeActionInterface $entityAttributeAction The entity attribute action instance
     *
     * @return void
     */
    public function setEntityAttributeAction(EntityAttributeActionInterface $entityAttributeAction)
    {
        $this->entityAttributeAction = $entityAttributeAction;
    }

    /**
     * Return's the entity attribute action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\EntityAttributeActionInterface The entity attribute action instance
     */
    public function getEntityAttributeAction()
    {
        return $this->entityAttributeAction;
    }

    /**
     * Load's and return's the EAV attribute with the passed code.
     *
     * @param string $attributeCode The code of the EAV attribute to load
     *
     * @return array The EAV attribute
     * @deprecated Since 2.0.2, use \TechDivision\Import\Attribute\Services\ExtendedAttributeBunchProcessorInterface::loadAttributeByEntityTypeIdAndAttributeCode() instead
     */
    public function loadAttributeByAttributeCode($attributeCode)
    {
        return $this->getAttributeRepository()->findOneByAttributeCode($attributeCode);
    }

    /**
     * Return's the EAV attribute label with the passed attribute code and store ID.
     *
     * @param string  $attributeCode The attribute code of the EAV attribute label to return
     * @param integer $storeId       The store ID of the EAV attribute label to return
     *
     * @return array The EAV attribute label
     */
    public function loadAttributeLabelByAttributeCodeAndStoreId($attributeCode, $storeId)
    {
        return $this->getAttributeLabelRepository()->findOneByAttributeCodeAndStoreId($attributeCode, $storeId);
    }

    /**
     * Load's and return's the EAV attribute option with the passed code, store ID and value.
     *
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option
     * @deprecated Since 2.0.2, use \TechDivision\Import\Attribute\Services\ExtendedAttributeBunchProcessorInterface::loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValue() instead
     */
    public function loadAttributeOptionByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value)
    {
        return $this->getAttributeOptionRepository()->findOneByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value);
    }

    /**
     * Load's and return's the EAV attribute option value with the passed code, store ID and value.
     *
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option value
     * @deprecated Since 2.0.2, use \TechDivision\Import\Attribute\Services\ExtendedAttributeBunchProcessorInterface::loadAttributeOptionValueByEntityTypeIdAndAttributeCodeAndStoreIdAndValue() instead
     */
    public function loadAttributeOptionValueByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value)
    {
        return $this->getEavAttributeOptionValueRepository()->findOneByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value);
    }

    /**
     * Load's and return's the EAV attribute option value with the passed option ID and store ID.
     *
     * @param string  $optionId The option ID
     * @param integer $storeId  The store ID of the attribute option to load
     *
     * @return array The EAV attribute option value
     */
    public function loadAttributeOptionValueByOptionIdAndStoreId($optionId, $storeId)
    {
        return $this->getEavAttributeOptionValueRepository()->findOneByOptionIdAndStoreId($optionId, $storeId);
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
     * @deprecated Since 2.0.2, use \TechDivision\Import\Attribute\Services\ExtendedAttributeBunchProcessorInterface::loadAttributeOptionSwatchByEntityTypeIdAndAttributeCodeAndStoreIdAndValue() instead
     */
    public function loadAttributeOptionSwatchByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value, $type)
    {
        return $this->getAttributeOptionSwatchRepository()->findOneByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value, $type);
    }

    /**
     * Load's and return's the EAV attribute option swatch with the passed option ID and store ID
     *
     * @param integer $optionId The option ID of the attribute option swatch to load
     * @param integer $storeId  The store ID of the attribute option swatch to load
     *
     * @return array The EAV attribute option swatch
     */
    public function loadAttributeOptionSwatchByOptionIdAndStoreId($optionId, $storeId)
    {
        return $this->getAttributeOptionSwatchRepository()->findOneByOptionIdAndStoreId($optionId, $storeId);
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
     * Return's the EAV entity attribute with the passed entity type, attribute, attribute set and attribute group ID.
     *
     * @param integer $entityTypeId     The ID of the EAV entity attribute's entity type to return
     * @param integer $attributeId      The ID of the EAV entity attribute's attribute to return
     * @param integer $attributeSetId   The ID of the EAV entity attribute's attribute set to return
     * @param integer $attributeGroupId The ID of the EAV entity attribute's attribute group to return
     *
     * @return array The EAV entity attribute
     */
    public function loadEntityAttributeByEntityTypeAndAttributeIdAndAttributeSetIdAndAttributeGroupId($entityTypeId, $attributeId, $attributeSetId, $attributeGroupId)
    {
        return $this->getEntityAttributeRepository()->findOneByEntityTypeAndAttributeIdAndAttributeSetIdAndAttributeGroupId($entityTypeId, $attributeId, $attributeSetId, $attributeGroupId);
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
     * Persist the passed attribute label.
     *
     * @param array       $attributeLabel The attribute label to persist
     * @param string|null $name           The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistAttributeLabel(array $attributeLabel, $name = null)
    {
        $this->getAttributeLabelAction()->persist($attributeLabel, $name);
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
     * Persist the passed attribute option swatch.
     *
     * @param array       $attributeOptionSwatch The attribute option swatch to persist
     * @param string|null $name                  The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistAttributeOptionSwatch(array $attributeOptionSwatch, $name = null)
    {
        $this->getAttributeOptionSwatchAction()->persist($attributeOptionSwatch, $name);
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
     * Persist's the passed EAV entity attribute data and return's the ID.
     *
     * @param array       $entityAttribute The entity attribute data to persist
     * @param string|null $name            The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistEntityAttribute(array $entityAttribute, $name = null)
    {
        $this->getEntityAttributeAction()->persist($entityAttribute, $name);
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
