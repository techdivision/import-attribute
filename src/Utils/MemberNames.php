<?php

/**
 * TechDivision\Import\Attribute\Utils\MemberNames
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
 * Utility class containing the entities member names.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class MemberNames extends \TechDivision\Import\Utils\MemberNames
{

    /**
     * Name for the member 'attribute_model'.
     *
     * @var string
     */
    const ATTRIBUTE_MODEL = 'attribute_model';

    /**
     * Name for the member 'backend_model'.
     *
     * @var string
     */
    const BACKEND_MODEL = 'backend_model';

    /**
     * Name for the member 'backend_table'.
     *
     * @var string
     */
    const BACKEND_TABLE = 'backend_table';

    /**
     * Name for the member 'frontend_model'.
     *
     * @var string
     */
    const FRONTEND_MODEL = 'frontend_model';

    /**
     * Name for the member 'frontend_class'.
     *
     * @var string
     */
    const FRONTEND_CLASS = 'frontend_class';

    /**
     * Name for the member 'frontend_label'.
     *
     * @var string
     */
    const FRONTEND_LABEL = 'frontend_label';

    /**
     * Name for the member 'source_model'.
     *
     * @var string
     */
    const SOURCE_MODEL = 'source_model';

    /**
     * Name for the member 'is_required'.
     *
     * @var string
     */
    const IS_REQUIRED = 'is_required';

    /**
     * Name for the member 'default_value'.
     *
     * @var string
     */
    const DEFAULT_VALUE = 'default_value';

    /**
     * Name for the member 'is_unique'.
     *
     * @var string
     */
    const IS_UNIQUE = 'is_unique';

    /**
     * Name for the member 'is_user_defined'.
     *
     * @var string
     */
    const IS_USER_DEFINED = 'is_user_defined';

    /**
     * Name for the member 'note'.
     *
     * @var string
     */
    const NOTE = 'note';

    /**
     * Name for the member 'frontend_input_renderer'.
     *
     * @var string
     */
    const FRONTEND_INPUT_RENDERER = 'frontend_input_renderer';

    /**
     * Name for the member 'is_global'.
     *
     * @var string
     */
    const IS_GLOBAL = 'is_global';

    /**
     * Name for the member 'is_visible'.
     *
     * @var string
     */
    const IS_VISIBLE = 'is_visible';

    /**
     * Name for the member 'is_searchable'.
     *
     * @var string
     */
    const IS_SEARCHABLE = 'is_searchable';

    /**
     * Name for the member 'is_filterable'.
     *
     * @var string
     */
    const IS_FILTERABLE = 'is_filterable';

    /**
     * Name for the member 'is_comparable'.
     *
     * @var string
     */
    const IS_COMPARABLE = 'is_comparable';

    /**
     * Name for the member 'is_visible_on_front'.
     *
     * @var string
     */
    const IS_VISIBLE_ON_FRONT = 'is_visible_on_front';

    /**
     * Name for the member 'is_html_allowed_on_front'.
     *
     * @var string
     */
    const IS_HTML_ALLOWED_ON_FRONT = 'is_html_allowed_on_front';

    /**
     * Name for the member 'is_used_for_price_rules'.
     *
     * @var string
     */
    const IS_USED_FOR_PRICE_RULES = 'is_used_for_price_rules';

    /**
     * Name for the member 'is_filterable_in_search'.
     *
     * @var string
     */
    const IS_FILTERABLE_IN_SEARCH = 'is_filterable_in_search';

    /**
     * Name for the member 'used_in_product_listing'.
     *
     * @var string
     */
    const USED_IN_PRODUCT_LISTING = 'used_in_product_listing';

    /**
     * Name for the member 'used_for_sort_by'.
     *
     * @var string
     */
    const USED_FOR_SORT_BY = 'used_for_sort_by';

    /**
     * Name for the member 'apply_to'.
     *
     * @var string
     */
    const APPLY_TO = 'apply_to';

    /**
     * Name for the member 'is_visible_in_advanced_search'.
     *
     * @var string
     */
    const IS_VISIBLE_IN_ADVANCED_SEARCH = 'is_visible_in_advanced_search';

    /**
     * Name for the member 'position'.
     *
     * @var string
     */
    const POSITION = 'position';

    /**
     * Name for the member 'is_wysiwyg_enabled'.
     *
     * @var string
     */
    const IS_WYSIWYG_ENABLED = 'is_wysiwyg_enabled';

    /**
     * Name for the member 'is_used_for_promo_rules'.
     *
     * @var string
     */
    const IS_USED_FOR_PROMO_RULES = 'is_used_for_promo_rules';

    /**
     * Name for the member 'is_required_in_admin_store'.
     *
     * @var string
     */
    const IS_REQUIRED_IN_ADMIN_STORE = 'is_required_in_admin_store';

    /**
     * Name for the member 'is_used_in_grid'.
     *
     * @var string
     */
    const IS_USED_IN_GRID = 'is_used_in_grid';

    /**
     * Name for the member 'is_visible_in_grid'.
     *
     * @var string
     */
    const IS_VISIBLE_IN_GRID = 'is_visible_in_grid';

    /**
     * Name for the member 'is_filterable_in_grid'.
     *
     * @var string
     */
    const IS_FILTERABLE_IN_GRID = 'is_filterable_in_grid';

    /**
     * Name for the member 'search_weight'.
     *
     * @var string
     */
    const SEARCH_WEIGHT = 'search_weight';

    /**
     * Name for the member 'additional_data'.
     *
     * @var string
     */
    const ADDITIONAL_DATA = 'additional_data';

    /**
     * Name for the member 'sort_order'.
     *
     * @var string
     */
    const SORT_ORDER = 'sort_order';
}
