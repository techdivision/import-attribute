# M2IF - Attribute Import

[![Latest Stable Version](https://img.shields.io/packagist/v/techdivision/import-attribute.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-attribute) 
 [![Total Downloads](https://img.shields.io/packagist/dt/techdivision/import-attribute.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-attribute)
 [![License](https://img.shields.io/packagist/l/techdivision/import-attribute.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-attribute)
 [![Build Status](https://img.shields.io/travis/techdivision/import-attribute/master.svg?style=flat-square)](http://travis-ci.org/techdivision/import-attribute)
 [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/techdivision/import-attribute/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/techdivision/import-attribute/?branch=master)
 [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/techdivision/import-attribute/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/techdivision/import-attribute/?branch=master)

This library provides a generic approach to import attributes in an existing Magento 2 CE/EE instance.

## CSV File Structure

By default, the category import expects a CSV file with the following defaults

* UTF-8 encoding
* Date format is n/d/y, g:i A
* Values delimiter is a comma (,)
* Multiple value delimiter is a pipe (|)
* Text values are enclosed with double apostrophes (")
* Special chars are secaped with a backslash (\)

> Columns that doesn't contain a value are ignored by default. This means, it is **NOT** possible to delete or override
> an existing value with an empty value. To delete an existing value, the whole category has to be removed by running 
> an import with the `delete` operation. After that, the category with the new values can be imported by running an 
> `add-update` operation.

The CSV file with the attributes for the Magento 2 CE/EE consists of the following columns

| Column Name                   | Type     | Mandatory | Description                                                                           | Example |
|:------------------------------|:---------|:----------| :-------------------------------------------------------------------------------------|:--------|
| store_view_code               | varchar  | yes       |                                                                                       |         |
| attribute_set_code            | varchar  | yes       |                                                                                       |         |
| attribute_group_code          | varchar  | yes       |                                                                                       |         |
| entity_type_code              | varchar  | yes       |                                                                                       |         |
| attribute_code                | varchar  | yes       |                                                                                       |         |
| attribute_model               | varchar  | yes       |                                                                                       |         |
| attribute_options             | text     | yes       |                                                                                       |         |
| backend_model                 | varchar  | yes       |                                                                                       |         |
| backend_table                 | varchar  | yes       |                                                                                       |         |
| frontend_model                | varchar  | yes       |                                                                                       |         |
| frontend_input                | varchar  | yes       |                                                                                       |         |
| frontend_label                | varchar  | yes       |                                                                                       |         |
| frontend_class                | varchar  | yes       |                                                                                       |         |
| source_model                  | varchar  | yes       |                                                                                       |         |
| frontend_input_renderer       | varchar  | yes       |                                                                                       |         |
| apply_to                      | varchar  | yes       |                                                                                       |         |
| display_pattern               | varchar  | yes       |                                                                                       |         |
| backend_type                  | varchar  | no        |                                                                                       |         |
| position                      | int      | no        |                                                                                       |         |
| is_required                   | int      | no        |                                                                                       |         |
| is_user_defined               | int      | no        |                                                                                       |         |
| default_value                 | text     | no        |                                                                                       |         |
| is_unique                     | int      | no        |                                                                                       |         |
| note                          | varchar  | no        |                                                                                       |         |
| is_global                     | int      | no        |                                                                                       |         |
| is_visible                    | int      | no        |                                                                                       |         |
| is_searchable                 | int      | no        |                                                                                       |         |
| is_filterable                 | int      | no        |                                                                                       |         |
| is_comparable                 | int      | no        |                                                                                       |         |
| is_visible_on_front           | int      | no        |                                                                                       |         |
| is_html_allowed_on_front      | int      | no        |                                                                                       |         |
| is_used_for_price_rules       | int      | no        |                                                                                       |         |
| is_filterable_in_search       | int      | no        |                                                                                       |         |
| used_in_product_listing       | int      | no        |                                                                                       |         |
| used_for_sort_by              | int      | no        |                                                                                       |         |
| is_visible_in_advanced_search | int      | no        |                                                                                       |         |
| position                      | int      | no        |                                                                                       |         |
| is_wysiwyg_enabled            | int      | no        |                                                                                       |         |
| is_used_for_promo_rules       | int      | no        |                                                                                       |         |
| is_required_in_admin_store    | int      | no        |                                                                                       |         |
| is_used_in_grid               | int      | no        |                                                                                       |         |
| is_visible_in_grid            | int      | no        |                                                                                       |         |
| is_filterable_in_grid         | int      | no        |                                                                                       |         |
| search_weight                 | float    | no        |                                                                                       |         |
| additional_data               | text     | no        |                                                                                       |         |
| is_used_in_autocomplete       | int      | no        |                                                                                       |         |
| is_displayed_in_autocomplete  | varchar  | no        |                                                                                       |         |
| is_used_in_spellcheck         | int      | no        |                                                                                       |         |
| facet_min_coverage_rate       | int      | no        |                                                                                       |         |
| facet_max_size                | int      | no        |                                                                                       |         |
| facet_sort_order              | varchar  | no        |                                                                                       |         |
| display_precision             | int      | no        |                                                                                       |         |
