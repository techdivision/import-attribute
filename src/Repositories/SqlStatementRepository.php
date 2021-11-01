<?php

/**
 * TechDivision\Import\Attribute\Repositories\SqlStatementKeys
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Repositories;

use TechDivision\Import\Attribute\Utils\SqlStatementKeys;

/**
 * Repository class with the SQL statements to use.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class SqlStatementRepository extends \TechDivision\Import\Repositories\SqlStatementRepository
{

    /**
     * The SQL statements.
     *
     * @var array
     */
    private $statements = array(
        SqlStatementKeys::ATTRIBUTE =>
            'SELECT * FROM ${table:eav_attribute} WHERE attribute_id = :attribute_id',
        SqlStatementKeys::CATALOG_ATTRIBUTE =>
            'SELECT attribute_id,
                    frontend_input_renderer,
                    is_global,
                    is_visible,
                    is_searchable,
                    is_filterable,
                    is_comparable,
                    is_visible_on_front,
                    is_html_allowed_on_front,
                    is_used_for_price_rules,
                    is_filterable_in_search,
                    used_in_product_listing,
                    used_for_sort_by,
                    apply_to,
                    is_visible_in_advanced_search,
                    position,
                    is_wysiwyg_enabled,
                    is_used_for_promo_rules,
                    is_required_in_admin_store,
                    is_used_in_grid,
                    is_visible_in_grid,
                    is_filterable_in_grid,
                    search_weight,
                    additional_data
               FROM ${table:catalog_eav_attribute}
              WHERE attribute_id = :attribute_id',
        SqlStatementKeys::ATTRIBUTE_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE =>
            'SELECT *
               FROM ${table:eav_attribute}
              WHERE entity_type_id = :entity_type_id
                AND attribute_code = :attribute_code',
        SqlStatementKeys::ATTRIBUTE_LABEL_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID =>
            'SELECT t1.*
               FROM ${table:eav_attribute_label} t1,
                    ${table:eav_attribute} t2
              WHERE t2.attribute_code = :attribute_code
                AND t2.entity_type_id = :entity_type_id
                AND t1.attribute_id = t2.attribute_id
                AND t1.store_id = :store_id',
        SqlStatementKeys::ATTRIBUTE_OPTION_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE =>
            'SELECT t2.*
               FROM ${table:eav_attribute} t1,
                    ${table:eav_attribute_option} t2,
                    ${table:eav_attribute_option_value} t3
              WHERE t1.attribute_code = :attribute_code
                AND t1.entity_type_id = :entity_type_id
                AND t3.store_id = :store_id
                AND t3.value = BINARY :value
                AND t2.attribute_id = t1.attribute_id
                AND t2.option_id = t3.option_id',
        SqlStatementKeys::ATTRIBUTE_OPTION_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID_AND_SWATCH_AND_TYPE =>
            'SELECT t2.*
               FROM ${table:eav_attribute} t1,
                    ${table:eav_attribute_option} t2,
                    ${table:eav_attribute_option_swatch} t3
              WHERE t1.attribute_code = :attribute_code
                AND t1.entity_type_id = :entity_type_id
                AND t3.store_id = :store_id
                AND t3.value = BINARY :value
                AND t3.type = :type
                AND t2.attribute_id = t1.attribute_id
                AND t2.option_id = t3.option_id',
        SqlStatementKeys::ATTRIBUTE_OPTION_SWATCH_BY_OPTION_ID_AND_STORE_ID =>
            'SELECT t1.*
               FROM ${table:eav_attribute_option_swatch} t1
              WHERE t1.store_id = :store_id
                AND t1.option_id = :option_id',
        SqlStatementKeys::CATALOG_ATTRIBUTE_BY_ATTRIBUTE_CODE_AND_ENTITY_TYPE_ID =>
            'SELECT t2.*
               FROM ${table:eav_attribute} t1
         INNER JOIN ${table:catalog_eav_attribute} t2
              WHERE t1.attribute_code = :attribute_code
                AND t1.entity_type_id = :entity_type_id
                AND t2.attribute_id = t1.attribute_id',
        SqlStatementKeys::ENTITY_ATTRIBUTE_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_ID_AND_ATTRIBUTE_SET_ID_AND_ATTRIBUTE_GROUP_ID =>
            'SELECT *
               FROM ${table:eav_entity_attribute}
              WHERE entity_type_id = :entity_type_id
                AND attribute_id = :attribute_id
                AND attribute_set_id = :attribute_set_id
                AND attribute_group_id = :attribute_group_id',
        SqlStatementKeys::ENTITY_ATTRIBUTE_BY_ATTRIBUTE_ID_AND_ATTRIBUTE_SET_ID =>
            'SELECT *
               FROM ${table:eav_entity_attribute}
              WHERE attribute_id = :attribute_id
                AND attribute_set_id = :attribute_set_id',
        SqlStatementKeys::ATTRIBUTE_OPTION_SWATCH_BY_ENTITY_TYPE_ID_AND_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE_AND_TYPE =>
            'SELECT t3.*
               FROM ${table:eav_attribute} t1,
                    ${table:eav_attribute_option} t2,
                    ${table:eav_attribute_option_swatch} t3
              WHERE t1.attribute_code = :attribute_code
                AND t1.entity_type_id = :entity_type_id
                AND t3.store_id = :store_id
                AND t3.value = BINARY :value
                AND t3.type = :type
                AND t2.attribute_id = t1.attribute_id
                AND t2.option_id = t3.option_id',
        SqlStatementKeys::ATTRIBUTE_OPTION_BY_ATTRIBUTE_ID_ORDER_BY_SORT_ORDER_DESC =>
            'SELECT *
               FROM ${table:eav_attribute_option}
              WHERE attribute_id = :attribute_id
           ORDER BY sort_order DESC',
        SqlStatementKeys::CREATE_ATTRIBUTE =>
            'INSERT
               INTO ${table:eav_attribute}
                    (entity_type_id,
                     attribute_code,
                     attribute_model,
                     backend_model,
                     backend_type,
                     backend_table,
                     frontend_model,
                     frontend_input,
                     frontend_label,
                     frontend_class,
                     source_model,
                     is_required,
                     is_user_defined,
                     default_value,
                     is_unique,
                     note)
             VALUES (:entity_type_id,
                     :attribute_code,
                     :attribute_model,
                     :backend_model,
                     :backend_type,
                     :backend_table,
                     :frontend_model,
                     :frontend_input,
                     :frontend_label,
                     :frontend_class,
                     :source_model,
                     :is_required,
                     :is_user_defined,
                     :default_value,
                     :is_unique,
                     :note)',
        SqlStatementKeys::CREATE_ENTITY_ATTRIBUTE =>
            'INSERT
               INTO ${table:eav_entity_attribute}
                    (entity_type_id,
                     attribute_id,
                     attribute_set_id,
                     attribute_group_id,
                     sort_order)
             VALUES (:entity_type_id,
                     :attribute_id,
                     :attribute_set_id,
                     :attribute_group_id,
                     :sort_order)',
        SqlStatementKeys::CREATE_ATTRIBUTE_LABEL =>
            'INSERT
               INTO ${table:eav_attribute_label}
                    (attribute_id,
                     store_id,
                     value)
             VALUES (:attribute_id,
                     :store_id,
                     :value)',
        SqlStatementKeys::CREATE_ATTRIBUTE_OPTION =>
            'INSERT ${table:eav_attribute_option}
                    (${column-names:eav_attribute_option})
             VALUES (${column-placeholders:eav_attribute_option})',
        SqlStatementKeys::CREATE_ATTRIBUTE_OPTION_VALUE =>
            'INSERT
               INTO ${table:eav_attribute_option_value}
                    (option_id,
                     store_id,
                     value)
             VALUES (:option_id,
                     :store_id,
                     :value)',
        SqlStatementKeys::CREATE_ATTRIBUTE_OPTION_SWATCH =>
            'INSERT
               INTO ${table:eav_attribute_option_swatch}
                    (option_id,
                     store_id,
                     value,
                     type)
             VALUES (:option_id,
                    :store_id,
                    :value,
                    :type)',
        SqlStatementKeys::CREATE_CATALOG_ATTRIBUTE =>
            'INSERT INTO ${table:catalog_eav_attribute} (%s) VALUES (:%s)',
        SqlStatementKeys::UPDATE_ATTRIBUTE =>
            'UPDATE ${table:eav_attribute}
                SET entity_type_id = :entity_type_id,
                    attribute_code = :attribute_code,
                    attribute_model = :attribute_model,
                    backend_model = :backend_model,
                    backend_type = :backend_type,
                    backend_table = :backend_table,
                    frontend_model = :frontend_model,
                    frontend_input = :frontend_input,
                    frontend_label = :frontend_label,
                    frontend_class = :frontend_class,
                    source_model = :source_model,
                    is_required = :is_required,
                    is_user_defined = :is_user_defined,
                    default_value = :default_value,
                    is_unique = :is_unique,
                    note = :note
              WHERE attribute_id = :attribute_id',
        SqlStatementKeys::UPDATE_CATALOG_ATTRIBUTE =>
            'UPDATE ${table:catalog_eav_attribute} SET %s WHERE attribute_id = :%s',
        SqlStatementKeys::UPDATE_ENTITY_ATTRIBUTE =>
            'UPDATE ${table:eav_entity_attribute}
                SET entity_type_id = :entity_type_id,
                    attribute_id = :attribute_id,
                    attribute_set_id = :attribute_set_id,
                    attribute_group_id = :attribute_group_id,
                    sort_order = :sort_order
              WHERE entity_attribute_id = :entity_attribute_id',
        SqlStatementKeys::UPDATE_ATTRIBUTE_LABEL =>
            'UPDATE ${table:eav_attribute_label}
                SET attribute_id = :attribute_id,
                    store_id = :store_id,
                    value = :value
              WHERE attribute_label_id = :attribute_label_id',
        SqlStatementKeys::UPDATE_ATTRIBUTE_OPTION =>
            'UPDATE ${table:eav_attribute_option}
                SET ${column-values:eav_attribute_option}
              WHERE option_id = :option_id',
        SqlStatementKeys::UPDATE_ATTRIBUTE_OPTION_VALUE =>
            'UPDATE ${table:eav_attribute_option_value}
                SET option_id = :option_id,
                    store_id = :store_id,
                    value = :value
              WHERE value_id = :value_id',
        SqlStatementKeys::UPDATE_ATTRIBUTE_OPTION_SWATCH =>
            'UPDATE ${table:eav_attribute_option_swatch}
                SET option_id = :option_id,
                    store_id = :store_id,
                    value = :value,
                    type = :type
              WHERE swatch_id = :swatch_id',
        SqlStatementKeys::DELETE_ATTRIBUTE =>
            'DELETE FROM ${table:eav_attribute} WHERE attribute_id = :attribute_id',
        SqlStatementKeys::DELETE_ENTITY_ATTRIBUTE =>
            'DELETE FROM ${table:eav_entity_attribute} WHERE entity_attribute_id = :entity_attribute_id',
        SqlStatementKeys::DELETE_ATTRIBUTE_LABEL =>
            'DELETE FROM ${table:eav_attribute_label} WHERE attribute_label_id = :attribute_label_id',
        SqlStatementKeys::DELETE_ATTRIBUTE_OPTION =>
            'DELETE FROM ${table:eav_attribute_option} WHERE option_id = :option_id',
        SqlStatementKeys::DELETE_ATTRIBUTE_OPTION_VALUE =>
            'DELETE FROM ${table:eav_attribute_option_value} WHERE value_id = :value_id',
        SqlStatementKeys::DELETE_ATTRIBUTE_OPTION_SWATCH =>
            'DELETE FROM ${table:eav_attribute_option_swatch} WHERE swatch_id = :swatch_id',
        SqlStatementKeys::DELETE_CATALOG_ATTRIBUTE =>
            'DELETE FROM ${table:catalog_eav_attribute} WHERE attribute_id = :attribute_id',
        SqlStatementKeys::DELETE_ATTRIBUTE_BY_ATTRIBUTE_CODE =>
            'DELETE FROM ${table:eav_attribute} WHERE attribute_code = :attribute_code'
    );

    /**
     * Initializes the SQL statement repository with the primary key and table prefix utility.
     *
     * @param \IteratorAggregate<\TechDivision\Import\Dbal\Utils\SqlCompilerInterface> $compilers The array with the compiler instances
     */
    public function __construct(\IteratorAggregate $compilers)
    {

        // pass primary key + table prefix utility to parent instance
        parent::__construct($compilers);

        // compile the SQL statements
        $this->compile($this->statements);
    }
}
