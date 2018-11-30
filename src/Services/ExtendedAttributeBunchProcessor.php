<?php

/**
 * TechDivision\Import\Attribute\Services\ExtendedAttributeBunchProcessor
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
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Services;

use TechDivision\Import\Connection\ConnectionInterface;
use TechDivision\Import\Attribute\Repositories\AttributeLabelRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\EavAttributeOptionRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\EavAttributeOptionValueRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\EavAttributeOptionSwatchRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\CatalogAttributeRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\EntityAttributeRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\EavAttributeRepositoryInterface;
use TechDivision\Import\Attribute\Repositories\EavEntityTypeRepositoryInterface;
use TechDivision\Import\Attribute\Actions\AttributeActionInterface;
use TechDivision\Import\Attribute\Actions\AttributeLabelActionInterface;
use TechDivision\Import\Attribute\Actions\AttributeOptionActionInterface;
use TechDivision\Import\Attribute\Actions\AttributeOptionValueActionInterface;
use TechDivision\Import\Attribute\Actions\AttributeOptionSwatchActionInterface;
use TechDivision\Import\Attribute\Actions\CatalogAttributeActionInterface;
use TechDivision\Import\Attribute\Actions\EntityAttributeActionInterface;

/**
 * The extended attribute bunch processor implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2018 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class ExtendedAttributeBunchProcessor extends AttributeBunchProcessor implements ExtendedAttributeBunchProcessorInterface
{

    /**
     * The entity type repository instance.
     *
     * @var \TechDivision\Import\Repositories\EavEntityTypeRepositoryInterface
     */
    protected $entityTypeRepository;

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     *
     * @param \TechDivision\Import\Connection\ConnectionInterface                                     $connection                        The connection to use
     * @param \TechDivision\Import\Attribute\Repositories\EavAttributeRepositoryInterface             $attributeRepository               The attribute repository instance
     * @param \TechDivision\Import\Attribute\Repositories\AttributeLabelRepositoryInterface           $attributeLabelRepository          The attribute label repository instance
     * @param \TechDivision\Import\Attribute\Repositories\EavAttributeOptionRepositoryInterface       $attributeOptionRepository         The attribute repository instance
     * @param \TechDivision\Import\Attribute\Repositories\EavAttributeOptionValueRepositoryInterface  $eavAttributeOptionValueRepository The EAV attribute option value repository to use
     * @param \TechDivision\Import\Attribute\Repositories\EavAttributeOptionSwatchRepositoryInterface $attributeOptionSwatchRepository   The attribute repository swatch instance
     * @param \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepositoryInterface         $catalogAttributeRepository        The catalog attribute repository instance
     * @param \TechDivision\Import\Attribute\Repositories\EntityAttributeRepositoryInterface          $entityAttributeRepository         The entity attribute repository instance
     * @param \TechDivision\Import\Repositories\EavEntityTypeRepositoryInterface                      $entityTypeRepository              The entity type repository instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeActionInterface                         $attributeAction                   The attribute action instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeLabelActionInterface                    $attributeLabelAction              The attribute label action instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionActionInterface                   $attributeOptionAction             The attribute option action instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionValueActionInterface              $attributeOptionValueAction        The attribute option value action instance
     * @param \TechDivision\Import\Attribute\Actions\AttributeOptionSwatchActionInterface             $attributeOptionSwatchAction       The attribute option swatch action instance
     * @param \TechDivision\Import\Attribute\Actions\CatalogAttributeActionInterface                  $catalogAttributeAction            The catalog attribute action instance
     * @param \TechDivision\Import\Attribute\Actions\EntityAttributeActionInterface                   $entityAttributeAction             The entity attribute action instance
     */
    public function __construct(
        ConnectionInterface $connection,
        EavAttributeRepositoryInterface $attributeRepository,
        AttributeLabelRepositoryInterface $attributeLabelRepository,
        EavAttributeOptionRepositoryInterface $attributeOptionRepository,
        EavAttributeOptionValueRepositoryInterface $eavAttributeOptionValueRepository,
        EavAttributeOptionSwatchRepositoryInterface $attributeOptionSwatchRepository,
        CatalogAttributeRepositoryInterface $catalogAttributeRepository,
        EntityAttributeRepositoryInterface $entityAttributeRepository,
        EavEntityTypeRepositoryInterface $entityTypeRepository,
        AttributeActionInterface $attributeAction,
        AttributeLabelActionInterface $attributeLabelAction,
        AttributeOptionActionInterface $attributeOptionAction,
        AttributeOptionValueActionInterface $attributeOptionValueAction,
        AttributeOptionSwatchActionInterface $attributeOptionSwatchAction,
        CatalogAttributeActionInterface $catalogAttributeAction,
        EntityAttributeActionInterface $entityAttributeAction
    ) {

        // set the entity type repository
        $this->setEntityTypeRepository($entityTypeRepository);

        // pass the instances to the parent constructor
        parent::__construct(
            $connection,
            $attributeRepository,
            $attributeLabelRepository,
            $attributeOptionRepository,
            $eavAttributeOptionValueRepository,
            $attributeOptionSwatchRepository,
            $catalogAttributeRepository,
            $entityAttributeRepository,
            $attributeAction,
            $attributeLabelAction,
            $attributeOptionAction,
            $attributeOptionValueAction,
            $attributeOptionSwatchAction,
            $catalogAttributeAction,
            $entityAttributeAction
        );
    }

    /**
     * Sets the EAV entity type repository.
     *
     * @param \TechDivision\Import\Attribute\Repositories\EavEntityTypeRepositoryInterface $entitTypeRepository The repository instance
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
     * @return \TechDivision\Import\Attribute\Repositories\EavEntityTypeRepositoryInterface The repository instance
     */
    public function getEntityTypeRepository()
    {
        return $this->entityTypeRepository;
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
}
