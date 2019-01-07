<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeObserver
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

namespace TechDivision\Import\Attribute\Observers;

use TechDivision\Import\Utils\BackendTypeKeys;
use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * Observer that create's the EAV attribute itself.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeObserver extends AbstractAttributeImportObserver
{

    /**
     * The attribute processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

    /**
     * Initializes the observer with the passed subject instance.
     *
     * @param \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface $attributeBunchProcessor The attribute bunch processor instance
     */
    public function __construct(AttributeBunchProcessorInterface $attributeBunchProcessor)
    {
        $this->attributeBunchProcessor = $attributeBunchProcessor;
    }

    /**
     * Process the observer's business logic.
     *
     * @return void
     */
    protected function process()
    {

        // query whether or not, we've found a new attribute code => means we've found a new attribute
        if ($this->hasBeenProcessed($this->getValue(ColumnKeys::ATTRIBUTE_CODE))) {
            return;
        }

        // prepare the attribue values
        $attribute = $this->initializeAttribute($this->prepareAttributes());

        // insert the entity and set the entity ID
        $this->setLastAttributeId($this->persistAttribute($attribute));
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // map the entity type code to the ID
        $entityType = $this->getEntityType($this->getValue(ColumnKeys::ENTITY_TYPE_CODE));
        $entityTypeId = $entityType[MemberNames::ENTITY_TYPE_ID];

        // load the data from the row
        $attributeCode = $this->getValue(ColumnKeys::ATTRIBUTE_CODE);
        $attributeModel = $this->getValue(ColumnKeys::ATTRIBUTE_MODEL);
        $backendModel = $this->getValue(ColumnKeys::BACKEND_MODEL);
        $backendType = $this->getValue(ColumnKeys::BACKEND_TYPE, BackendTypeKeys::BACKEND_TYPE_STATIC);
        $backendTable = $this->getValue(ColumnKeys::BACKEND_TABLE);
        $frontendModel = $this->getValue(ColumnKeys::FRONTEND_MODEL);
        $frontendInput = $this->getValue(ColumnKeys::FRONTEND_INPUT);
        $frontendLabel = $this->getValue(ColumnKeys::FRONTEND_LABEL);
        $frontendClass = $this->getValue(ColumnKeys::FRONTEND_CLASS);
        $sourceModel = $this->getValue(ColumnKeys::SOURCE_MODEL);
        $isRequired = $this->getValue(ColumnKeys::IS_REQUIRED, 0);
        $isUserDefined = $this->getValue(ColumnKeys::IS_USER_DEFINED, 1);
        $isUnique = $this->getValue(ColumnKeys::IS_UNIQUE, 0);
        $note = $this->getValue(ColumnKeys::NOTE);

        // return the prepared product
        return $this->initializeEntity(
            array(
                MemberNames::ENTITY_TYPE_ID  => $entityTypeId,
                MemberNames::ATTRIBUTE_CODE  => $attributeCode,
                MemberNames::ATTRIBUTE_MODEL => $attributeModel,
                MemberNames::BACKEND_MODEL   => $backendModel,
                MemberNames::BACKEND_TYPE    => $backendType,
                MemberNames::BACKEND_TABLE   => $backendTable,
                MemberNames::FRONTEND_MODEL  => $frontendModel,
                MemberNames::FRONTEND_INPUT  => $frontendInput,
                MemberNames::FRONTEND_LABEL  => $frontendLabel,
                MemberNames::FRONTEND_CLASS  => $frontendClass,
                MemberNames::SOURCE_MODEL    => $sourceModel,
                MemberNames::IS_REQUIRED     => $isRequired,
                MemberNames::IS_USER_DEFINED => $isUserDefined,
                MemberNames::DEFAULT_VALUE   => null,
                MemberNames::IS_UNIQUE       => $isUnique,
                MemberNames::NOTE            => $note
            )
        );
    }

    /**
     * Initialize the attribute with the passed attributes and returns an instance.
     *
     * @param array $attr The attribute attributes
     *
     * @return array The initialized attribute
     */
    protected function initializeAttribute(array $attr)
    {
        return $attr;
    }

    /**
     * Return's the attribute bunch processor instance.
     *
     * @return \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface The attribute bunch processor instance
     */
    protected function getAttributeBunchProcessor()
    {
        return $this->attributeBunchProcessor;
    }

    /**
     * Map's the passed attribute code to the attribute ID that has been created recently.
     *
     * @param string $attributeCode The attribute code that has to be mapped
     *
     * @return void
     */
    protected function addAttributeCodeIdMapping($attributeCode)
    {
        $this->getSubject()->addAttributeCodeIdMapping($attributeCode);
    }

    /**
     * Queries whether or not the attribute with the passed code has already been processed.
     *
     * @param string $attributeCode The attribute code to check
     *
     * @return boolean TRUE if the path has been processed, else FALSE
     */
    protected function hasBeenProcessed($attributeCode)
    {
        return $this->getSubject()->hasBeenProcessed($attributeCode);
    }

    /**
     * Set's the ID of the attribute that has been created recently.
     *
     * @param integer $lastAttributeId The attribute ID
     *
     * @return void
     */
    protected function setLastAttributeId($lastAttributeId)
    {
        $this->getSubject()->setLastAttributeId($lastAttributeId);
    }

    /**
     * Persist the passed attribute.
     *
     * @param array $attribute The attribute to persist
     *
     * @return void
     */
    protected function persistAttribute(array $attribute)
    {
        return $this->getAttributeBunchProcessor()->persistAttribute($attribute);
    }

    /**
     * Return's the entity type for the passed code.
     *
     * @param string $entityTypeCode The entity type code
     *
     * @return array The requested entity type
     * @throws \Exception Is thrown, if the entity type with the passed code is not available
     */
    protected function getEntityType($entityTypeCode)
    {
        return $this->getSubject()->getEntityType($entityTypeCode);
    }
}
