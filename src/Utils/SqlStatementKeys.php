<?php

/**
 * TechDivision\Import\Attribute\Utils\SqlStatementKeys
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Utils;

/**
 * Utility class with keys of the SQL statements to use.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class SqlStatementKeys extends \TechDivision\Import\Utils\SqlStatementKeys
{

    /**
     * The SQL statement to load the EAV attribute with the passed attribute ID.
     *
     * @var string
     */
    const ATTRIBUTE = 'attribute';

    /**
     * The SQL statement to load the EAV catalog attribute with the passed attribute ID.
     *
     * @var string
     */
    const CATALOG_ATTRIBUTE = 'catalog_attribute';

    /**
     * The SQL statement to load the EAV attribute by its entity type ID and attribute code.
     *
     * @var string
     */
    const ATTRIBUTE_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE = 'attribute.by.entity_type_id.and.attribute_code';

    /**
     * The SQL statement to load the EAV attribute label by its entity type ID, attribute code and store ID.
     *
     * @var string
     */
    const ATTRIBUTE_LABEL_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID = 'attribute_label.by.entity_type_id.and.attribute_code.and.store_id';

    /**
     * The SQL statement to load the EAV attribute option by its entity type ID, attribute code, store ID and value.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE = 'attribute_option.by.entity_type_id.and.attribute_code.and.store_id.and.value';

    /**
     * The SQL statement to load the EAV attribute option by its entity type ID, attribute code, store ID and swatch and type.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID_AND_SWATCH_AND_TYPE = 'attribute_option.by.entity_type_id.and.attribute_code.and.store_id.and.swatch.and.type';

    /**
     * The SQL statement to load the EAV attribute option with the attribute with the given ID and the highest sort order.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_BY_ATTRIBUTE_ID_ORDER_BY_SORT_ORDER_DESC = 'attribute_option.by.attribute_id.order_by.sort_order.desc';

    /**
     * The SQL statement to load the EAV attribute option swtach by its entity type ID, attribute code, store ID, value and type.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_SWATCH_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE_AND_TYPE = 'attribute_option_swatch.by.entity_type_id.and.attribute_code.and.store_id.and.value.and.type';

    /**
     * The SQL statement to load the EAV attribute option swtach by its attribute code, store ID, value and type.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_SWATCH_BY_OPTION_ID_AND_STORE_ID = 'attribute_option_swatch.by.option_id.and.store_id';

    /**
     * The SQL statement to load the EAV catalog attribute by its attribute code and entity type ID.
     *
     * @var string
     */
    const CATALOG_ATTRIBUTE_BY_ATTRIBUTE_CODE_AND_ENTITY_TYPE_ID = 'catalog_attribute.by.attribute_code.and.entity_type_id';

    /**
     * The SQL statement to load the EAV entity attribute by its attribute and attribute set ID.
     *
     * @var string
     */
    const ENTITY_ATTRIBUTE_BY_ATTRIBUTE_ID_AND_ATTRIBUTE_SET_ID = 'entity_attribute.by.attribute_id.and.attribute_set_id';

    /**
     * The SQL statement to load the EAV entity attribute by its entity type, attribute, attribute set and attribute group ID.
     *
     * @var string
     */
    const ENTITY_ATTRIBUTE_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_ID_AND_ATTRIBUTE_SET_ID_AND_ATTRIBUTE_GROUP_ID = 'entity_attribute.by.entity_type_id.and.attribute_id.and.attribute_set_id.and.attribute_group_id';

    /**
     * The SQL statement to create a new EAV attribute.
     *
     * @var string
     */
    const CREATE_ATTRIBUTE = 'create.attribute';

    /**
     * The SQL statement to create a new EAV entity attribute.
     *
     * @var string
     */
    const CREATE_ENTITY_ATTRIBUTE = 'create.entity_attribute';

    /**
     * The SQL statement to create a new EAV attribute label.
     *
     * @var string
     */
    const CREATE_ATTRIBUTE_LABEL = 'create.attribute_label';

    /**
     * The SQL statement to create a new EAV attribute option.
     *
     * @var string
     */
    const CREATE_ATTRIBUTE_OPTION = 'create.attribute_option';

    /**
     * The SQL statement to create a new EAV attribute option value.
     *
     * @var string
     */
    const CREATE_ATTRIBUTE_OPTION_VALUE = 'create.attribute_option_value';

    /**
     * The SQL statement to create a new EAV attribute option swatch value.
     *
     * @var string
     */
    const CREATE_ATTRIBUTE_OPTION_SWATCH = 'create.attribute_option_swatch';

    /**
     * The SQL statement to create a new EAV catalog attribute.
     *
     * @var string
     */
    const CREATE_CATALOG_ATTRIBUTE = 'create.catalog_attribute';

    /**
     * The SQL statement to update an existing EAV attribute.
     *
     * @var string
     */
    const UPDATE_ATTRIBUTE = 'update.attribute';

    /**
     * The SQL statement to update an existing EAV catalog attribute.
     *
     * @var string
     */
    const UPDATE_CATALOG_ATTRIBUTE = 'update.catalog_attribute';

    /**
     * The SQL statement to update an existing EAV attribute label.
     *
     * @var string
     */
    const UPDATE_ENTITY_ATTRIBUTE = 'update.entity_attribute';

    /**
     * The SQL statement to update an existing EAV attribute label.
     *
     * @var string
     */
    const UPDATE_ATTRIBUTE_LABEL = 'update.attribute_label';

    /**
     * The SQL statement to update an existing EAV attribute option.
     *
     * @var string
     */
    const UPDATE_ATTRIBUTE_OPTION = 'update.attribute_option';

    /**
     * The SQL statement to update an existing EAV attribute option value.
     *
     * @var string
     */
    const UPDATE_ATTRIBUTE_OPTION_VALUE = 'update.attribute_option_value';

    /**
     * The SQL statement to update an existing EAV attribute option value.
     *
     * @var string
     */
    const UPDATE_ATTRIBUTE_OPTION_SWATCH = 'update.attribute_option_swatch';

    /**
     * The SQL statement to remove a existing EAV attribute.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE = 'delete.attribute';

    /**
     * The SQL statement to remove a existing EAV entity attribute.
     *
     * @var string
     */
    const DELETE_ENTITY_ATTRIBUTE = 'delete.entity_attribute';

    /**
     * The SQL statement to remove a existing EAV attribute label.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE_LABEL = 'delete.attribute_label';

    /**
     * The SQL statement to remove a existing EAV attribute option.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE_OPTION = 'delete.attribute_option';

    /**
     * The SQL statement to remove a existing EAV attribute option value.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE_OPTION_VALUE = 'delete.attribute_option_value';

    /**
     * The SQL statement to remove a existing EAV attribute option swatch value.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE_OPTION_SWATCH = 'delete.attribute_option_swatch';

    /**
     * The SQL statement to remove a existing EAV catalog attribute.
     *
     * @var string
     */
    const DELETE_CATALOG_ATTRIBUTE = 'delete.catalog_attribute';

    /**
     * The SQL statement to remove a existing EAV attribute by its attribute code.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE_BY_ATTRIBUTE_CODE = 'delete.attribute.by.attribute_code';
}
