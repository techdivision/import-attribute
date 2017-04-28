<?php

/**
 * TechDivision\Import\Attribute\Utils\ColumnKeys
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
 * Utility class containing the CSV column names.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class ColumnKeys extends \TechDivision\Import\Utils\ColumnKeys
{

    /**
     * Name for the column 'attribute_group_code'.
     *
     * @var string
     */
    const ATTRIBUTE_GROUP_CODE = 'attribute_group_code';

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
    const USED_IN_PRODUCT_LISTING = 'used_in_product_listing ';

    /**
     * Name for the column 'used_for_sort_by'.
     *
     * @var string
     */
    CONST USED_FOR_SORT_BY = 'used_for_sort_by';

    /**
     * Name for the column 'apply_to'.
     *
     * @var string
     */
    CONST APPLY_TO = 'apply_to';

    /**
     * Name for the column 'is_visible_in_advanced_search'.
     *
     * @var string
     */
    CONST IS_VISIBLE_IN_ADVANCED_SEARCH = 'is_visible_in_advanced_search';

    /**
     * Name for the column 'position'.
     *
     * @var string
     */
    CONST POSITION = 'position';

    /**
     * Name for the column 'is_wysiwyg_enabled'.
     *
     * @var string
     */
    CONST IS_WYSIWYG_ENABLED = 'is_wysiwyg_enabled';

    /**
     * Name for the column 'is_used_for_promo_rules'.
     *
     * @var string
     */
    CONST IS_USED_FOR_PROMO_RULES = 'is_used_for_promo_rules';

    /**
     * Name for the column 'is_required_in_admin_store'.
     *
     * @var string
     */
    CONST IS_REQUIRED_IN_ADMIN_STORE = 'is_required_in_admin_store';

    /**
     * Name for the column 'is_used_in_grid'.
     *
     * @var string
     */
    CONST IS_USED_IN_GRID = 'is_used_in_grid';

    /**
     * Name for the column 'is_visible_in_grid'.
     *
     * @var string
     */
    CONST IS_VISIBLE_IN_GRID = 'is_visible_in_grid';

    /**
     * Name for the column 'is_filterable_in_grid'.
     *
     * @var string
     */
    CONST IS_FILTERABLE_IN_GRID = 'is_filterable_in_grid';

    /**
     * Name for the column 'search_weight'.
     *
     * @var string
     */
    CONST SEARCH_WEIGHT = 'search_weight';

    /**
     * Name for the column 'additional_data'.
     *
     * @var string
     */
    CONST ADDITIONAL_DATA = 'additional_data';
}
