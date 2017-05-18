<?php

/**
 * TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
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

/**
 * Interface for a attribute bunch processor.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
interface AttributeBunchProcessorInterface extends AttributeProcessorInterface
{

    /**
     * Return's the attribute repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\AttributeRepository The attribute repository instance
     */
    public function getAttributeRepository();

    /**
     * Return's the attribute label repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\AttributeRepository The attribute label repository instance
     */
    public function getAttributeLabelRepository();

    /**
     * Return's the attribute option repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\AttributeOptionRepository The attribute option repository instance
     */
    public function getAttributeOptionRepository();

    /**
     * Return's the repository to access EAV attribute option values.
     *
     * @return \TechDivision\Import\Product\Repositories\EavAttributeOptionValueRepository The repository instance
     */
    public function getEavAttributeOptionValueRepository();

    /**
     * Return's the attribute option swatch repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\AttributeOptionSwatchRepository The attribute option swatch repository instance
     */
    public function getAttributeOptionSwatchRepository();

    /**
     * Return's the catalog attribute repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\CatalogAttributeRepository The catalog attribute repository instance
     */
    public function getCatalogAttributeRepository();

    /**
     * Return's the entity attribute repository instance.
     *
     * @return \TechDivision\Import\Attribute\Repositories\EntityAttributeRepository The entity attribute repository instance
     */
    public function getEntityAttributeRepository();

    /**
     * Return's the attribute action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeAction The attribute action instance
     */
    public function getAttributeAction();

    /**
     * Return's the attribute label action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeAction The attribute label action instance
     */
    public function getAttributeLabelAction();

    /**
     * Return's the attribute option action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeOptionAction The attribute option action instance
     */
    public function getAttributeOptionAction();

    /**
     * Return's the attribute option value action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeOptionValueAction The attribute option value action instance
     */
    public function getAttributeOptionValueAction();

    /**
     * Return's the attribute option swatch action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\AttributeOptionSwatchAction The attribute option swatch action instance
     */
    public function getAttributeOptionSwatchAction();

    /**
     * Return's the catalog attribute action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\CatalogAttributeAction The catalog attribute action instance
     */
    public function getCatalogAttributeAction();

    /**
     * Return's the entity attribute action instance.
     *
     * @return \TechDivision\Import\Attribute\Actions\EntityAttributeAction The entity attribute action instance
     */
    public function getEntityAttributeAction();

    /**
     * Load's and return's the EAV attribute with the passed code.
     *
     * @param string $attributeCode The code of the EAV attribute to load
     *
     * @return array The EAV attribute
     */
    public function loadAttributeByAttributeCode($attributeCode);

    /**
     * Return's the EAV attribute label with the passed attribute code and store ID.
     *
     * @param string  $attributeCode The attribute code of the EAV attribute label to return
     * @param integer $storeId       The store ID of the EAV attribute label to return
     *
     * @return array The EAV attribute label
     */
    public function loadAttributeLabelByAttributeCodeAndStoreId($attributeCode, $storeId);

    /**
     * Load's and return's the EAV attribute option with the passed code, store ID and value.
     *
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option
     */
    public function loadAttributeOptionByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value);

    /**
     * Load's and return's the EAV attribute option value with the passed code, store ID and value.
     *
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option value
     */
    public function loadAttributeOptionValueByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value);

    /**
     * Load's and return's the EAV attribute option swatch with the passed code, store ID, value and type.
     *
     * @param string  $attributeCode The code of the EAV attribute option swatch to load
     * @param integer $storeId       The store ID of the attribute option swatch to load
     * @param string  $value         The value of the attribute option swatch to load
     * @param string  $type          The type of the attribute option swatch to load
     *
     * @return array The EAV attribute option swatch
     */
    public function loadAttributeOptionSwatchByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value, $type);

    /**
     * Load's and retur's the EAV catalog attribute with the passed ID.
     *
     * @param string $attributeId The ID of the EAV catalog attribute to return
     *
     * @return array The EAV catalog attribute
     */
    public function loadCatalogAttribute($attributeId);

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
    public function loadEntityAttributeByEntityTypeAndAttributeIdAndAttributeSetIdAndAttributeGroupId($entityTypeId, $attributeId, $attributeSetId, $attributeGroupId);

    /**
     * Persist's the passed EAV attribute data and return's the ID.
     *
     * @param array       $attribute The attribute data to persist
     * @param string|null $name      The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted attribute
     */
    public function persistAttribute(array $attribute, $name = null);

    /**
     * Persist the passed attribute label.
     *
     * @param array       $attributeLabel The attribute label to persist
     * @param string|null $name           The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistAttributeLabel(array $attributeLabel, $name = null);

    /**
     * Persist's the passed EAV attribute option data and return's the ID.
     *
     * @param array       $attributeOption The attribute option data to persist
     * @param string|null $name            The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted attribute
     */
    public function persistAttributeOption(array $attributeOption, $name = null);

    /**
     * Persist's the passed EAV attribute option value data and return's the ID.
     *
     * @param array       $attributeOptionValue The attribute option value data to persist
     * @param string|null $name                 The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted attribute
     */
    public function persistAttributeOptionValue(array $attributeOptionValue, $name = null);

    /**
     * Persist the passed attribute option swatch.
     *
     * @param array       $attributeOptionSwatch The attribute option swatch to persist
     * @param string|null $name                  The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistAttributeOptionSwatch(array $attributeOptionSwatch, $name = null);

    /**
     * Persist's the passed EAV catalog attribute data and return's the ID.
     *
     * @param array       $catalogAttribute The catalog attribute data to persist
     * @param string|null $name             The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistCatalogAttribute(array $catalogAttribute, $name = null);

    /**
     * Delete's the EAV attribute with the passed attributes.
     *
     * @param array       $row  The attributes of the EAV attribute to delete
     * @param string|null $name The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function deleteAttribute($row, $name = null);
}
