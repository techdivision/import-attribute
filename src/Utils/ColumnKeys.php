<?php

/**
 * TechDivision\Import\Attribute\Utils\ColumnKeys
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
 * Utility class containing the CSV column names.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class ColumnKeys extends \TechDivision\Import\Utils\ColumnKeys
{

    /**
     * Name for the column 'attribute_option_values'.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_VALUES = 'attribute_option_values';

    /**
     * Name for the column 'attribute_option_sort_order'.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_SORT_ORDER = 'attribute_option_sort_order';

    /**
     * Name for the column 'attribute_option_swatch'.
     *
     * @var string
     */
    const ATTRIBUTE_OPTION_SWATCH = 'attribute_option_swatch';

    /**
     * Name for the column 'attribute_group_name'.
     *
     * @var string
     */
    const ATTRIBUTE_GROUP_NAME = 'attribute_group_name';

    /**
     * Name for the column 'attribute_set_name'.
     *
     * @var string
     */
    const ATTRIBUTE_SET_NAME = 'attribute_set_name';

    /**
     * Name for the column 'attribute_model'.
     *
     * @var string
     */
    const ATTRIBUTE_MODEL = 'attribute_model';

    /**
     * Name for the column 'backend_model'.
     *
     * @var string
     */
    const BACKEND_MODEL = 'backend_model';

    /**
     * Name for the column 'backend_type'.
     *
     * @var string
     */
    const BACKEND_TYPE = 'backend_type';

    /**
     * Name for the column 'backend_table'.
     *
     * @var string
     */
    const BACKEND_TABLE = 'backend_table';

    /**
     * Name for the column 'entity_type_code'.
     *
     * @var string
     */
    const ENTITY_TYPE_CODE = 'entity_type_code';

    /**
     * Name for the column 'frontend_class'.
     *
     * @var string
     */
    const FRONTEND_CLASS = 'frontend_class';

    /**
     * Name for the column 'frontend_label'.
     *
     * @var string
     */
    const FRONTEND_LABEL = 'frontend_label';

    /**
     * Name for the column 'frontend_model'.
     *
     * @var string
     */
    const FRONTEND_MODEL = 'frontend_model';

    /**
     * Name for the column 'frontend_input'.
     *
     * @var string
     */
    const FRONTEND_INPUT = 'frontend_input';

    /**
     * Name for the column 'source_model'.
     *
     * @var string
     */
    const SOURCE_MODEL = 'source_model';

    /**
     * Name for the column 'is_required'.
     *
     * @var string
     */
    const IS_REQUIRED = 'is_required';

    /**
     * Name for the column 'is_user_defined'.
     *
     * @var string
     */
    const IS_USER_DEFINED = 'is_user_defined';

    /**
     * Name for the column 'is_unique'.
     *
     * @var string
     */
    const IS_UNIQUE = 'is_unique';

    /**
     * Name for the column 'note'.
     *
     * @var string
     */
    const NOTE = 'note';

    /**
     * Name for the column 'default_value'.
     *
     * @var string
     */
    const DEFAULT_VALUE = 'default_value';

    /**
     * Name for the column 'frontend_input_renderer'.
     *
     * @var string
     */
    const FRONTEND_INPUT_RENDERER = 'frontend_input_renderer';

    /**
     * Name for the column 'is_global'.
     *
     * @var string
     */
    const IS_GLOBAL = 'is_global';

    /**
     * Name for the column 'is_visible'.
     *
     * @var string
     */
    const IS_VISIBLE = 'is_visible';

    /**
     * Name for the column 'is_searchable'.
     *
     * @var string
     */
    const IS_SEARCHABLE = 'is_searchable';

    /**
     * Name for the column 'is_filterable'.
     *
     * @var string
     */
    const IS_FILTERABLE = 'is_filterable';

    /**
     * Name for the column 'is_comparable'.
     *
     * @var string
     */
    const IS_COMPARABLE = 'is_comparable';

    /**
     * Name for the column 'is_visible_on_front'.
     *
     * @var string
     */
    const IS_VISIBLE_ON_FRONT = 'is_visible_on_front';

    /**
     * Name for the column 'is_html_allowed_on_front'.
     *
     * @var string
     */
    const IS_HTML_ALLOWED_ON_FRONT = 'is_html_allowed_on_front';

    /**
     * Name for the column 'is_used_for_price_rules'.
     *
     * @var string
     */
    const IS_USED_FOR_PRICE_RULES = 'is_used_for_price_rules';

    /**
     * Name for the column 'is_filterable_in_search'.
     *
     * @var string
     */
    const IS_FILTERABLE_IN_SEARCH = 'is_filterable_in_search';

    /**
     * Name for the column 'used_in_product_listing'.
     *
     * @var string
     */
    const USED_IN_PRODUCT_LISTING = 'used_in_product_listing';

    /**
     * Name for the column 'used_for_sort_by'.
     *
     * @var string
     */
    const USED_FOR_SORT_BY = 'used_for_sort_by';

    /**
     * Name for the column 'apply_to'.
     *
     * @var string
     */
    const APPLY_TO = 'apply_to';

    /**
     * Name for the column 'is_visible_in_advanced_search'.
     *
     * @var string
     */
    const IS_VISIBLE_IN_ADVANCED_SEARCH = 'is_visible_in_advanced_search';

    /**
     * Name for the column 'position'.
     *
     * @var string
     */
    const POSITION = 'position';

    /**
     * Name for the column 'is_wysiwyg_enabled'.
     *
     * @var string
     */
    const IS_WYSIWYG_ENABLED = 'is_wysiwyg_enabled';

    /**
     * Name for the column 'is_used_for_promo_rules'.
     *
     * @var string
     */
    const IS_USED_FOR_PROMO_RULES = 'is_used_for_promo_rules';

    /**
     * Name for the column 'is_required_in_admin_store'.
     *
     * @var string
     */
    const IS_REQUIRED_IN_ADMIN_STORE = 'is_required_in_admin_store';

    /**
     * Name for the column 'is_used_in_grid'.
     *
     * @var string
     */
    const IS_USED_IN_GRID = 'is_used_in_grid';

    /**
     * Name for the column 'is_visible_in_grid'.
     *
     * @var string
     */
    const IS_VISIBLE_IN_GRID = 'is_visible_in_grid';

    /**
     * Name for the column 'is_filterable_in_grid'.
     *
     * @var string
     */
    const IS_FILTERABLE_IN_GRID = 'is_filterable_in_grid';

    /**
     * Name for the column 'search_weight'.
     *
     * @var string
     */
    const SEARCH_WEIGHT = 'search_weight';

    /**
     * Name for the column 'additional_data'.
     *
     * @var string
     */
    const ADDITIONAL_DATA = 'additional_data';

    /**
     * Name for the column 'swatch_type'.
     *
     * @var string
     */
    const SWATCH_TYPE = 'swatch_type';

    /**
     * Name for the column 'swatch_value'.
     *
     * @var string
     */
    const SWATCH_VALUE = 'swatch_value';

    /**
     * Name for the column 'type'.
     *
     * @var string
     */
    const TYPE = 'type';

    /**
     * Name for the column 'admin_store_value'.
     *
     * @var string
     */
    const ADMIN_STORE_VALUE = 'admin_store_value';

    /**
     * Name for the column 'value'.
     *
     * @var string
     */
    const VALUE = 'value';
}
