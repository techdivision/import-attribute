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
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Services;

use TechDivision\Import\Loaders\LoaderInterface;
use TechDivision\Import\Dbal\Actions\ActionInterface;
use TechDivision\Import\Dbal\Connection\ConnectionInterface;
use TechDivision\Import\Repositories\EavEntityTypeRepositoryInterface;
use TechDivision\Import\Repositories\EavAttributeOptionValueRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\AttributeRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\AttributeLabelRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\AttributeOptionRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\EntityAttributeRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\CatalogAttributeRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\AttributeOptionSwatchRepositoryInterface;

/**
 * The attribute bunch processor implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeBunchProcessor implements AttributeBunchProcessorInterface
{

    /**
     * A connection to use.
     *
     * @var \TechDivision\Import\Dbal\Connection\ConnectionInterface
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
     * The EAV entity type repository instance.
     *
     * @var \TechDivision\Import\Repositories\EavEntityTypeRepositoryInterface
     */
    protected $entityTypeRepository;

    /**
     * The attribute action instance.
     *
     * @var \TechDivision\Import\Dbal\Actions\ActionInterface
     */
    protected $attributeAction;

    /**
     * The attribute label action instance.
     *
     * @var \TechDivision\Import\Dbal\Actions\ActionInterface
     */
    protected $attributeLabelAction;

    /**
     * The attribute option action instance.
     *
     * @var \TechDivision\Import\Dbal\Actions\ActionInterface
     */
    protected $attributeOptionAction;

    /**
     * The attribute option value action instance.
     *
     * @var \TechDivision\Import\Dbal\Actions\ActionInterface
     */
    protected $attributeOptionValueAction;

    /**
     * The attribute option swatch action instance.
     *
     * @var \TechDivision\Import\Dbal\Actions\ActionInterface
     */
    protected $attributeOptionSwatchAction;

    /**
     * The attribute action instance.
     *
     * @var \TechDivision\Import\Dbal\Actions\ActionInterface
     */
    protected $catalogAttributeAction;

    /**
     * The entity attribute action instance.
     *
     * @var \TechDivision\Import\Dbal\Actions\ActionInterface
     */
    protected $entityAttributeAction;

    /**
     * The raw entity loader instance.
     *
     * @var \TechDivision\Import\Loaders\LoaderInterface
     */
    protected $rawEntityLoader;

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     *
     * @param \TechDivision\Import\Dbal\Connection\ConnectionInterface                                  $connection                        The connection to use
     * @param \TechDivision\Import\Attribute\Repositories\AttributeRepositoryInterface             $attributeRepository               The attribute repository instance
     * @param \TechDivision\Import\Attribute\Repositories\AttributeLabelRepositoryInterface        $attributeLabelRepository          The attribute label repository instance
     * @param \TechDivision\Import\Attribute\Repositories\AttributeOptionRepositoryInterface       $attributeOptionRepository         The attribute repository instance
     * @param \TechDivision\Import\Repositories\EavAttributeOptionValueRepositoryInterface         $eavAttributeOptionValueRepository The EAV attribute option value repository to use
     * @param \TechDivision\Import\Attribute\Repositories\AttributeOptionSwatchRepositoryInterface $attributeOptionSwatchRepository   The attribute repository swatch instance
     * @param \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepositoryInterface      $catalogAttributeRepository        The catalog attribute repository instance
     * @param \TechDivision\Import\Attribute\Repositories\EntityAttributeRepositoryInterface       $entityAttributeRepository         The entity attribute repository instance
     * @param \TechDivision\Import\Repositories\EavEntityTypeRepositoryInterface                   $entityTypeRepository              The entity type repository instance
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface                                         $attributeAction                   The attribute action instance
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface                                         $attributeLabelAction              The attribute label action instance
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface                                         $attributeOptionAction             The attribute option action instance
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface                                         $attributeOptionValueAction        The attribute option value action instance
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface                                         $attributeOptionSwatchAction       The attribute option swatch action instance
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface                                         $catalogAttributeAction            The catalog attribute action instance
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface                                         $entityAttributeAction             The entity attribute action instance
     * @param \TechDivision\Import\Loaders\LoaderInterface                                         $rawEntityLoader                   The raw entity loader instance
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
        EavEntityTypeRepositoryInterface $entityTypeRepository,
        ActionInterface $attributeAction,
        ActionInterface $attributeLabelAction,
        ActionInterface $attributeOptionAction,
        ActionInterface $attributeOptionValueAction,
        ActionInterface $attributeOptionSwatchAction,
        ActionInterface $catalogAttributeAction,
        ActionInterface $entityAttributeAction,
        LoaderInterface $rawEntityLoader
    ) {
        $this->setConnection($connection);
        $this->setAttributeRepository($attributeRepository);
        $this->setAttributeLabelRepository($attributeLabelRepository);
        $this->setAttributeOptionRepository($attributeOptionRepository);
        $this->setEavAttributeOptionValueRepository($eavAttributeOptionValueRepository);
        $this->setAttributeOptionSwatchRepository($attributeOptionSwatchRepository);
        $this->setCatalogAttributeRepository($catalogAttributeRepository);
        $this->setEntityAttributeRepository($entityAttributeRepository);
        $this->setEntityTypeRepository($entityTypeRepository);
        $this->setAttributeAction($attributeAction);
        $this->setAttributeLabelAction($attributeLabelAction);
        $this->setAttributeOptionAction($attributeOptionAction);
        $this->setAttributeOptionValueAction($attributeOptionValueAction);
        $this->setAttributeOptionSwatchAction($attributeOptionSwatchAction);
        $this->setCatalogAttributeAction($catalogAttributeAction);
        $this->setEntityAttributeAction($entityAttributeAction);
        $this->setRawEntityLoader($rawEntityLoader);
    }

    /**
     * Set's the raw entity loader instance.
     *
     * @param \TechDivision\Import\Loaders\LoaderInterface $rawEntityLoader The raw entity loader instance to set
     *
     * @return void
     */
    public function setRawEntityLoader(LoaderInterface $rawEntityLoader)
    {
        $this->rawEntityLoader = $rawEntityLoader;
    }

    /**
     * Return's the raw entity loader instance.
     *
     * @return \TechDivision\Import\Loaders\LoaderInterface The raw entity loader instance
     */
    public function getRawEntityLoader()
    {
        return $this->rawEntityLoader;
    }

    /**
     * Set's the passed connection.
     *
     * @param \TechDivision\Import\Dbal\Connection\ConnectionInterface $connection The connection to set
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
     * @return \TechDivision\Import\Dbal\Connection\ConnectionInterface The connection instance
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
     * @return \TechDivision\Import\Attribute\Repositories\AttributeLabelRepositoryInterface The attribute label repository instance
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
     * Sets the EAV entity type repository.
     *
     * @param \TechDivision\Import\Repositories\EavEntityTypeRepositoryInterface $entitTypeRepository The repository instance
     *
     * @return void
     */
    public function setEntityTypeRepository(EavEntityTypeRepositoryInterface $entitTypeRepository)
    {
        $this->entityTypeRepository = $entitTypeRepository;
    }

    /**
     * Returns the EAV entity type repository.
     *
     * @return \TechDivision\Import\Repositories\EavEntityTypeRepositoryInterface The repository instance
     */
    public function getEntityTypeRepository()
    {
        return $this->entityTypeRepository;
    }

    /**
     * Set's the attribute action instance.
     *
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface $attributeAction The attribute action instance
     *
     * @return void
     */
    public function setAttributeAction(ActionInterface $attributeAction)
    {
        $this->attributeAction = $attributeAction;
    }

    /**
     * Return's the attribute action instance.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The attribute action instance
     */
    public function getAttributeAction()
    {
        return $this->attributeAction;
    }

    /**
     * Set's the attribute label action instance.
     *
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface $attributeLabelAction The attribute label action instance
     *
     * @return void
     */
    public function setAttributeLabelAction(ActionInterface $attributeLabelAction)
    {
        $this->attributeLabelAction = $attributeLabelAction;
    }

    /**
     * Return's the attribute label action instance.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The attribute label action instance
     */
    public function getAttributeLabelAction()
    {
        return $this->attributeLabelAction;
    }

    /**
     * Set's the attribute option action instance.
     *
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface $attributeOptionAction The attribute option action instance
     *
     * @return void
     */
    public function setAttributeOptionAction(ActionInterface $attributeOptionAction)
    {
        $this->attributeOptionAction = $attributeOptionAction;
    }

    /**
     * Return's the attribute option action instance.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The attribute option action instance
     */
    public function getAttributeOptionAction()
    {
        return $this->attributeOptionAction;
    }

    /**
     * Set's the attribute option value action instance.
     *
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface $attributeOptionValueAction The attribute option value action instance
     *
     * @return void
     */
    public function setAttributeOptionValueAction(ActionInterface $attributeOptionValueAction)
    {
        $this->attributeOptionValueAction = $attributeOptionValueAction;
    }

    /**
     * Return's the attribute option value action instance.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The attribute option value action instance
     */
    public function getAttributeOptionValueAction()
    {
        return $this->attributeOptionValueAction;
    }

    /**
     * Set's the attribute option swatch action instance.
     *
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface $attributeOptionSwatchAction The attribute option swatch action instance
     *
     * @return void
     */
    public function setAttributeOptionSwatchAction(ActionInterface $attributeOptionSwatchAction)
    {
        $this->attributeOptionSwatchAction = $attributeOptionSwatchAction;
    }

    /**
     * Return's the attribute option swatch action instance.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The attribute option swatch action instance
     */
    public function getAttributeOptionSwatchAction()
    {
        return $this->attributeOptionSwatchAction;
    }

    /**
     * Set's the catalog attribute action instance.
     *
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface $catalogAttributeAction The catalog attribute action instance
     *
     * @return void
     */
    public function setCatalogAttributeAction(ActionInterface $catalogAttributeAction)
    {
        $this->catalogAttributeAction = $catalogAttributeAction;
    }

    /**
     * Return's the catalog attribute action instance.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The catalog attribute action instance
     */
    public function getCatalogAttributeAction()
    {
        return $this->catalogAttributeAction;
    }

    /**
     * Set's the entity attribute action instance.
     *
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface $entityAttributeAction The entity attribute action instance
     *
     * @return void
     */
    public function setEntityAttributeAction(ActionInterface $entityAttributeAction)
    {
        $this->entityAttributeAction = $entityAttributeAction;
    }

    /**
     * Return's the entity attribute action instance.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The entity attribute action instance
     */
    public function getEntityAttributeAction()
    {
        return $this->entityAttributeAction;
    }

    /**
     * Load's and return's a raw entity without primary key but the mandatory members only and nulled values.
     *
     * @param string $entityTypeCode The entity type code to return the raw entity for
     * @param array  $data           An array with data that will be used to initialize the raw entity with
     *
     * @return array The initialized entity
     */
    public function loadRawEntity($entityTypeCode, array $data = array())
    {
        return $this->getRawEntityLoader()->load($entityTypeCode, $data);
    }

    /**
     * Return's the EAV attribute label with the passed attribute code and store ID.
     *
     * @param integer $entityTypeId  The ID of the EAV entity attribute to return the label for
     * @param string  $attributeCode The attribute code of the EAV attribute label to return
     * @param integer $storeId       The store ID of the EAV attribute label to return
     *
     * @return array The EAV attribute label
     */
    public function loadAttributeLabelByEntityTypeIdAndAttributeCodeAndStoreId($entityTypeId, $attributeCode, $storeId)
    {
        return $this->getAttributeLabelRepository()->findOneByEntityTypeIdAndAttributeCodeAndStoreId($entityTypeId, $attributeCode, $storeId);
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
     * Return's the EAV entity attribute with the passed attribute and attribute set ID.
     *
     * @param integer $attributeId    The ID of the EAV entity attribute's attribute to return
     * @param integer $attributeSetId The ID of the EAV entity attribute's attribute set to return
     *
     * @return array The EAV entity attribute
     */
    public function loadEntityAttributeByAttributeIdAndAttributeSetId($attributeId, $attributeSetId)
    {
        return $this->getEntityAttributeRepository()->findOneByAttributeIdAndAttributeSetId($attributeId, $attributeSetId);
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
    public function loadEntityAttributeByEntityTypeIdAndAttributeIdAndAttributeSetIdAndAttributeGroupId($entityTypeId, $attributeId, $attributeSetId, $attributeGroupId)
    {
        return $this->getEntityAttributeRepository()->findOneByEntityTypeIdAndAttributeIdAndAttributeSetIdAndAttributeGroupId($entityTypeId, $attributeId, $attributeSetId, $attributeGroupId);
    }

    /**
     * Return's an EAV entity type with the passed entity type code.
     *
     * @param string $entityTypeCode The code of the entity type to return
     *
     * @return array The entity type with the passed entity type code
     */
    public function loadEntityTypeByEntityTypeCode($entityTypeCode)
    {
        return $this->getEntityTypeRepository()->findOneByEntityTypeCode($entityTypeCode);
    }

    /**
     * Return's the EAV attribute with the passed entity type ID and code.
     *
     * @param integer $entityTypeId  The entity type ID of the EAV attribute to return
     * @param string  $attributeCode The code of the EAV attribute to return
     *
     * @return array The EAV attribute
     */
    public function loadAttributeByEntityTypeIdAndAttributeCode($entityTypeId, $attributeCode)
    {
        return $this->getAttributeRepository()->findOneByEntityIdAndAttributeCode($entityTypeId, $attributeCode);
    }

    /**
     * Load's and return's the EAV attribute option with the passed entity type ID, code, store ID and value.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load the option for
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option
     */
    public function loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value)
    {
        return $this->getAttributeOptionRepository()->findOneByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value);
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
    public function loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndSwatchAndType($entityTypeId, $attributeCode, $storeId, $swatch, $type)
    {
        return $this->getAttributeOptionRepostory()->findOneByEntityTypeIdAndAttributeCodeAndStoreIdAndSwatchAndType($entityTypeId, $attributeCode, $storeId, $swatch, $type);
    }

    /**
     * Load's and return's the EAV attribute option value with the passed code, store ID and value.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load th option for
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option value
     */
    public function loadAttributeOptionValueByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value)
    {
        return $this->getEavAttributeOptionValueRepository()->findOneByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value);
    }

    /**
     * Load's and return's the EAV attribute option swatch with the passed code, store ID, value and type.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load th option for
     * @param string  $attributeCode The code of the EAV attribute option swatch to load
     * @param integer $storeId       The store ID of the attribute option swatch to load
     * @param string  $value         The value of the attribute option swatch to load
     * @param string  $type          The type of the attribute option swatch to load
     *
     * @return array The EAV attribute option swatch
     */
    public function loadAttributeOptionSwatchByEntityTypeIdAndAttributeCodeAndStoreIdAndValueAndType($entityTypeId, $attributeCode, $storeId, $value, $type)
    {
        return $this->getAttributeOptionSwatchRepository()->findOneByEntityTypeIdAndAttributeCodeAndStoreIdAndValueAndType($entityTypeId, $attributeCode, $storeId, $value, $type);
    }

    /**
     * Returns the EAV attribute option of attribute with the passed ID with the highest sort order.
     *
     * @param integer $attributeId The ID of the attribute to return the EAV option with the highest sort order for
     *
     * @return array|null The EAV attribute option with the highest sort order
     */
    public function loadAttributeOptionByAttributeIdAndHighestSortOrder($attributeId)
    {
        return $this->getAttributeOptionRepository()->findOneByAttributeIdAndHighestSortOrder($attributeId);
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
