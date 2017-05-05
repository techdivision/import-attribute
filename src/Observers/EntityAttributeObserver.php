<?php

/**
 * TechDivision\Import\Attribute\Observers\EntityAttributeObserver
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

use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Subjects\SubjectInterface;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * Observer that create's the EAV entity attribute itself.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class EntityAttributeObserver extends AbstractAttributeImportObserver
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
     * @param \TechDivision\Import\Subjects\SubjectInterface                           $subject                 The observer's subject instance
     * @param \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface $attributeBunchProcessor The attribute bunch processor instance
     */
    public function __construct(
        SubjectInterface $subject,
        AttributeBunchProcessorInterface $attributeBunchProcessor
    ) {

        // pass the subject through to the parend observer
        parent::__construct($subject);

        // initialize the attribute bunch processor
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

        // prepare the EAV entity attribue values
        $entityAttribute = $this->initializeAttribute($this->prepareAttributes());

        // insert the EAV entity attribute
        $this->persistEntityAttribute($entityAttribute);
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the last attribute ID
        $attributeId = $this->getLastAttributeId();

        // map the entity type code to the ID
        $entityType = $this->getEntityType($entityTypeCode = $this->getValue(ColumnKeys::ENTITY_TYPE_CODE));
        $entityTypeId = $entityType[MemberNames::ENTITY_TYPE_ID];

        // load the attribute set ID
        $attributeSet = $this->getAttributeSetByAttributeSetNameAndEntityTypeCode($attributeSetName = $this->getValue(ColumnKeys::ATTRIBUTE_SET_NAME), $entityTypeCode);
        $attributeSetId = $attributeSet[MemberNames::ATTRIBUTE_SET_ID];

        // load the attribute group ID
        $attributeGroup = $this->getAttributeGroupByEntityTypeCodeAndAttributeSetNameAndAttributeGroupName($entityTypeCode, $attributeSetName, $this->getValue(ColumnKeys::ATTRIBUTE_GROUP_NAME));
        $attributeGroupId = $attributeGroup[MemberNames::ATTRIBUTE_GROUP_ID];

        // return the prepared product
        return $this->initializeEntity(
            array(
                MemberNames::ATTRIBUTE_ID       => $attributeId,
                MemberNames::ENTITY_TYPE_ID     => $entityTypeId,
                MemberNames::ATTRIBUTE_SET_ID   => $attributeSetId,
                MemberNames::ATTRIBUTE_GROUP_ID => $attributeGroupId,
                MemberNames::SORT_ORDER         => 0
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
     * Return's the ID of the attribute that has been created recently.
     *
     * @return integer The attribute ID
     */
    protected function getLastAttributeId()
    {
        return $this->getSubject()->getLastAttributeId();
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

    /**
     * Return's the attribute set with the passed attribute set name.
     *
     * @param string $attributeSetName The name of the requested attribute set
     * @param string $entityTypeCode   The entity type code of the requested attribute set
     *
     * @return array The EAV attribute set
     * @throws \Exception Is thrown, if the attribute set with the passed name is not available
     */
    protected function getAttributeSetByAttributeSetNameAndEntityTypeCode($attributeSetName, $entityTypeCode)
    {
        return $this->getSubject()->getAttributeSetByAttributeSetNameAndEntityTypeCode($attributeSetName, $entityTypeCode);
    }

    /**
     * Return's the attribute group with the passed attribute set/group name.
     *
     * @param string $entityTypeCode     The entity type code of the requested attribute group
     * @param string $attributeSetName   The name of the requested attribute group's attribute set
     * @param string $attributeGroupName The name of the requested attribute group
     *
     * @return array The EAV attribute group
     * @throws \Exception Is thrown, if the attribute group with the passed attribute set/group name is not available
     */
    protected function getAttributeGroupByEntityTypeCodeAndAttributeSetNameAndAttributeGroupName($entityTypeCode, $attributeSetName, $attributeGroupName)
    {
        return $this->getSubject()->getAttributeGroupByEntityTypeCodeAndAttributeSetNameAndAttributeGroupName($entityTypeCode, $attributeSetName, $attributeGroupName);
    }

    /**
     * Persist the passed entity attribute.
     *
     * @param array $entityAttribute The entity attribute to persist
     *
     * @return void
     */
    protected function persistEntityAttribute(array $entityAttribute)
    {
        return $this->getAttributeBunchProcessor()->persistEntityAttribute($entityAttribute);
    }
}
