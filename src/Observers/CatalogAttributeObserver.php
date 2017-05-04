<?php

/**
 * TechDivision\Import\Attribute\Observers\CatalogAttributeObserver
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
 * Observer that create's the EAV catalog attribute itself.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class CatalogAttributeObserver extends AbstractAttributeImportObserver
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

        // initialize and persist the EAV catalog attribute
        $this->persistCatalogAttribute($this->initializeAttribute($this->prepareAttributes()));
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the recently created EAV attribute ID
        $attributeId = $this->getLastAttributeId();

        // load the data from the row
        $frontendInputRenderer = $this->getValue(ColumnKeys::FRONTEND_INPUT_RENDERER);
        $isGlobal = $this->getValue(ColumnKeys::IS_GLOBAL, 1);
        $isVisible = $this->getValue(ColumnKeys::IS_VISIBLE, 1);
        $isSearchable = $this->getValue(ColumnKeys::IS_SEARCHABLE, 0);
        $isFilterable = $this->getValue(ColumnKeys::IS_FILTERABLE, 0);
        $isComparable = $this->getValue(ColumnKeys::IS_COMPARABLE, 0);
        $isVisibleOnFront = $this->getValue(ColumnKeys::IS_VISIBLE_ON_FRONT, 0);
        $isHtmlAllowedOnFront = $this->getValue(ColumnKeys::IS_HTML_ALLOWED_ON_FRONT, 0);
        $isUsedForPriceRules = $this->getValue(ColumnKeys::IS_USED_FOR_PRICE_RULES, 0);
        $isFilterableInSearch = $this->getValue(ColumnKeys::IS_FILTERABLE_IN_SEARCH, 0);
        $usedInProductListing = $this->getValue(ColumnKeys::USED_IN_PRODUCT_LISTING, 0);
        $usedForSortBy = $this->getValue(ColumnKeys::USED_FOR_SORT_BY, 0);
        $applyTo = $this->getValue(ColumnKeys::APPLY_TO);
        $isVisibleInAdvancedSearch = $this->getValue(ColumnKeys::IS_VISIBLE_IN_ADVANCED_SEARCH, 0);
        $position = $this->getValue(ColumnKeys::POSITION, 0);
        $isWysiwygEnabled = $this->getValue(ColumnKeys::IS_WYSIWYG_ENABLED, 0);
        $isUsedForPromoRules = $this->getValue(ColumnKeys::IS_USED_FOR_PROMO_RULES, 0);
        $isRequiredInAdminStore = $this->getValue(ColumnKeys::IS_REQUIRED_IN_ADMIN_STORE, 0);
        $isUsedInGrid = $this->getValue(ColumnKeys::IS_USED_IN_GRID, 0);
        $isVisibleInGrid = $this->getValue(ColumnKeys::IS_VISIBLE_IN_GRID, 0);
        $isFilterableInGrid = $this->getValue(ColumnKeys::IS_FILTERABLE_IN_GRID, 0);
        $searchWeight = $this->getValue(ColumnKeys::SEARCH_WEIGHT, 1);
        $additionalData = $this->getValue(ColumnKeys::ADDITIONAL_DATA);

        // return the prepared product
        return $this->initializeEntity(
            array(
                MemberNames::ATTRIBUTE_ID                  => $attributeId,
                MemberNames::FRONTEND_INPUT_RENDERER       => $frontendInputRenderer,
                MemberNames::IS_GLOBAL                     => $isGlobal,
                MemberNames::IS_VISIBLE                    => $isVisible,
                MemberNames::IS_SEARCHABLE                 => $isSearchable,
                MemberNames::IS_FILTERABLE                 => $isFilterable,
                MemberNames::IS_COMPARABLE                 => $isComparable,
                MemberNames::IS_VISIBLE_ON_FRONT           => $isVisibleOnFront,
                MemberNames::IS_HTML_ALLOWED_ON_FRONT      => $isHtmlAllowedOnFront,
                MemberNames::IS_USED_FOR_PRICE_RULES       => $isUsedForPriceRules,
                MemberNames::IS_FILTERABLE_IN_SEARCH       => $isFilterableInSearch,
                MemberNames::USED_IN_PRODUCT_LISTING       => $usedInProductListing,
                MemberNames::USED_FOR_SORT_BY              => $usedForSortBy,
                MemberNames::APPLY_TO                      => $applyTo,
                MemberNames::IS_VISIBLE_IN_ADVANCED_SEARCH => $isVisibleInAdvancedSearch,
                MemberNames::POSITION                      => $position,
                MemberNames::IS_WYSIWYG_ENABLED            => $isWysiwygEnabled,
                MemberNames::IS_USED_FOR_PROMO_RULES       => $isUsedForPromoRules,
                MemberNames::IS_REQUIRED_IN_ADMIN_STORE    => $isRequiredInAdminStore,
                MemberNames::IS_USED_IN_GRID               => $isUsedInGrid,
                MemberNames::IS_VISIBLE_IN_GRID            => $isVisibleInGrid,
                MemberNames::IS_FILTERABLE_IN_GRID         => $isFilterableInGrid,
                MemberNames::SEARCH_WEIGHT                 => $searchWeight,
                MemberNames::ADDITIONAL_DATA               => $additionalData
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
     * Return's the ID of the attribute that has been created recently.
     *
     * @return integer The attribute ID
     */
    protected function getLastAttributeId()
    {
        return $this->getSubject()->getLastAttributeId();
    }

    /**
     * Persist the passed EAV catalog attribute.
     *
     * @param array $catalogAttribute The EAV catalog attribute to persist
     *
     * @return void
     */
    protected function persistCatalogAttribute(array $catalogAttribute)
    {
        return $this->getAttributeBunchProcessor()->persistCatalogAttribute($catalogAttribute);
    }
}
