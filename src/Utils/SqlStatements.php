<?php

/**
 * TechDivision\Import\Attribute\Utils\SqlStatements
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

namespace TechDivision\Import\Attribute\Utils;

/**
 * Utility class with the SQL statements to use.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class SqlStatements extends \TechDivision\Import\Utils\SqlStatements
{

    /**
     * The SQL statement to load the EAV attribute with the passed attribute ID.
     *
     * @var string
     */
    const ATTRIBUTE = 'SELECT * FROM eav_attribute WHERE attribute_id = :attribute_id';

    /**
     * The SQL statement to load the EAV catalog attribute with the passed attribute ID.
     *
     * @var string
     */
    const CATALOG_ATTRIBUTE = 'SELECT * FROM catalog_eav_attribute WHERE attribute_id = :attribute_id';

    /**
     * The SQL statement to load the EAV attribute by its attribute code.
     *
     * @var string
     */
    const ATTRIBUTE_BY_ATTRIBUTE_CODE = 'SELECT * FROM eav_attribute WHERE attribute_code = :attribute_code';

    /**
     * The SQL statement to load the EAV attribute option by its attribute code, store ID and value.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_BY_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE = 'SELECT t2.*
                                                                         FROM eav_attribute t1,
                                                                              eav_attribute_option t2,
                                                                              eav_attribute_option_value t3
                                                                        WHERE t1.attribute_code = :attribute_code
                                                                          AND t3.store_id = :store_id
                                                                          AND t3.value = :value
                                                                          AND t2.attribute_id = t1.attribute_id
                                                                          AND t2.option_id = t3.option_id';

    /**
     * The SQL statement to load the EAV attribute option value by its attribute code, store ID and value.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_VALUE_BY_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE = 'SELECT t3.*
                                                                               FROM eav_attribute t1,
                                                                                    eav_attribute_option t2,
                                                                                    eav_attribute_option_value t3
                                                                              WHERE t1.attribute_code = :attribute_code
                                                                                AND t3.store_id = :store_id
                                                                                AND t3.value = :value
                                                                                AND t2.attribute_id = t1.attribute_id
                                                                                AND t2.option_id = t3.option_id';

    /**
     * The SQL statement to load the EAV attribute option swtach by its attribute code, store ID, value and type.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_SWATCH_BY_ATTRIBUTE_CODE_AND_STORE_ID_AND_VALUE_AND_TYPE = 'SELECT t3.*
                                                                                         FROM eav_attribute t1,
                                                                                              eav_attribute_option t2,
                                                                                              eav_attribute_option_swatch t3
                                                                                        WHERE t1.attribute_code = :attribute_code
                                                                                          AND t3.store_id = :store_id
                                                                                          AND t3.value = :value
                                                                                          AND t3.type = :type
                                                                                          AND t2.attribute_id = t1.attribute_id
                                                                                          AND t2.option_id = t3.option_id';

    /**
     * The SQL statement to load the EAV catalog attribute by its attribute code and entity type ID.
     *
     * @var string
     */
    const CATALOG_ATTRIBUTE_BY_ATTRIBUTE_CODE_AND_ENTITY_TYPE_ID = 'SELECT t2.*
                                                                      FROM eav_attribute t1
                                                                INNER JOIN catalog_eav_attribute t2
                                                                     WHERE t1.attribute_code = :attribute_code
                                                                       AND t1.entity_type_id = :entity_type_id
                                                                       AND t2.attribute_id = t1.attribute_id';

    /**
     * The SQL statement to create a new EAV attribute.
     *
     * @var string
     */
    const CREATE_ATTRIBUTE = 'INSERT
                                INTO eav_attribute (entity_type_id,
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
                                                    note
                                     )
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
                                      :note)';

    /**
     * The SQL statement to create a new EAV attribute option.
     *
     * @var string
     */
    const CREATE_ATTRIBUTE_OPTION = 'INSERT
                                       INTO eav_attribute_option (attribute_id,
                                                                  sort_order
                                            )
                                     VALUES (:attribute_id,
                                             :sort_order)';

    /**
     * The SQL statement to create a new EAV attribute option value.
     *
     * @var string
     */
    const CREATE_ATTRIBUTE_OPTION_VALUE = 'INSERT
                                             INTO eav_attribute_option_value (option_id,
                                                                              store_id,
                                                                              value
                                                  )
                                           VALUES (:option_id,
                                                   :store_id,
                                                   :value)';

    /**
     * The SQL statement to create a new EAV attribute option swatch value.
     *
     * @var string
     */
    const CREATE_ATTRIBUTE_OPTION_SWATCH = 'INSERT
                                              INTO eav_attribute_option_swatch (option_id,
                                                                                store_id,
                                                                                value,
                                                                                type
                                                  )
                                           VALUES (:option_id,
                                                   :store_id,
                                                   :value,
                                                   :type)';

    /**
     * The SQL statement to create a new EAV catalog attribute.
     *
     * @var string
     */
    const CREATE_CATALOG_ATTRIBUTE = 'INSERT
                                        INTO catalog_eav_attribute (attribute_id,
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
                                             )
                                      VALUES (:attribute_id,
                                              :frontend_input_renderer,
                                              :is_global,
                                              :is_visible,
                                              :is_searchable,
                                              :is_filterable,
                                              :is_comparable,
                                              :is_visible_on_front,
                                              :is_html_allowed_on_front,
                                              :is_used_for_price_rules,
                                              :is_filterable_in_search,
                                              :used_in_product_listing,
                                              :used_for_sort_by,
                                              :apply_to,
                                              :is_visible_in_advanced_search,
                                              :position,
                                              :is_wysiwyg_enabled,
                                              :is_used_for_promo_rules,
                                              :is_required_in_admin_store,
                                              :is_used_in_grid,
                                              :is_visible_in_grid,
                                              :is_filterable_in_grid,
                                              :search_weight,
                                              :additional_data)';

    /**
     * The SQL statement to update an existing EAV attribute.
     *
     * @var string
     */
    const UPDATE_ATTRIBUTE = 'UPDATE eav_attribute
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
                               WHERE attribute_id = :attribute_id';

    /**
     * The SQL statement to update an existing EAV catalog attribute.
     *
     * @var string
     */
    const UPDATE_CATALOG_ATTRIBUTE = 'UPDATE catalog_eav_attribute
                                         SET frontend_input_renderer = :frontend_input_renderer,
                                             is_global = :is_global,
                                             is_visible = :is_visible,
                                             is_searchable = :is_searchable,
                                             is_filterable = :is_filterable,
                                             is_comparable = :is_comparable,
                                             is_visible_on_front = :is_visible_on_front,
                                             is_html_allowed_on_front = :is_html_allowed_on_front,
                                             is_used_for_price_rules = :is_used_for_price_rules,
                                             is_filterable_in_search = :is_filterable_in_search,
                                             used_in_product_listing = :used_in_product_listing,
                                             used_for_sort_by = :used_for_sort_by,
                                             apply_to = :apply_to,
                                             is_visible_in_advanced_search = :is_visible_in_advanced_search,
                                             position = :position,
                                             is_wysiwyg_enabled = :is_wysiwyg_enabled,
                                             is_used_for_promo_rules = :is_used_for_promo_rules,
                                             is_required_in_admin_store = :is_required_in_admin_store,
                                             is_used_in_grid = :is_used_in_grid,
                                             is_visible_in_grid = :is_visible_in_grid,
                                             is_filterable_in_grid = :is_filterable_in_grid,
                                             search_weight = :search_weight,
                                             additional_data = :additional_data
                                       WHERE attribute_id = :attribute_id';

    /**
     * The SQL statement to update an existing EAV attribute option.
     *
     * @var string
     */
    const UPDATE_ATTRIBUTE_OPTION = 'UPDATE eav_attribute_option
                                        SET attribute_id = :attribute_id,
                                            sort_order = :sort_order
                                      WHERE option_id = :option_id';

    /**
     * The SQL statement to update an existing EAV attribute option value.
     *
     * @var string
     */
    const UPDATE_ATTRIBUTE_OPTION_VALUE = 'UPDATE eav_attribute_option_value
                                              SET option_id = :option_id,
                                                  store_id = :store_id,
                                                  value = :value
                                            WHERE value_id = :value_id';

    /**
     * The SQL statement to update an existing EAV attribute option value.
     *
     * @var string
     */
    const UPDATE_ATTRIBUTE_OPTION_SWATCH = 'UPDATE eav_attribute_option_swatch
                                               SET option_id = :option_id,
                                                   store_id = :store_id,
                                                   value = :value,
                                                   type = :type
                                             WHERE swatch_id = :swatch_id';

    /**
     * The SQL statement to remove a existing EAV attribute.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE = 'DELETE FROM eav_attribute WHERE attribute_id = :attribute_id';

    /**
     * The SQL statement to remove a existing EAV attribute option.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE_OPTION = 'DELETE FROM eav_attribute_option WHERE option_id = :option_id';

    /**
     * The SQL statement to remove a existing EAV attribute option value.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE_OPTION_VALUE = 'DELETE FROM eav_attribute_option_value WHERE value_id = :value_id';

    /**
     * The SQL statement to remove a existing EAV attribute option swatch value.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE_OPTION_SWATCH= 'DELETE FROM eav_attribute_option_swatch WHERE swatch_id = :swatch_id';

    /**
     * The SQL statement to remove a existing EAV catalog attribute.
     *
     * @var string
     */
    const DELETE_CATALOG_ATTRIBUTE = 'DELETE FROM catalog_eav_attribute WHERE attribute_id = :attribute_id';

    /**
     * The SQL statement to remove a existing EAV attribute by its attribute code.
     *
     * @var string
     */
    const DELETE_ATTRIBUTE_BY_ATTRIBUTE_CODE = 'DELETE FROM eav_attribute WHERE attribute_code = :attribute_code';
}
