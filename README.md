# M2IF - Attribute Import

[![Latest Stable Version](https://img.shields.io/packagist/v/techdivision/import-attribute.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-attribute) 
 [![Total Downloads](https://img.shields.io/packagist/dt/techdivision/import-attribute.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-attribute)
 [![License](https://img.shields.io/packagist/l/techdivision/import-attribute.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-attribute)
 [![Build Status](https://img.shields.io/travis/techdivision/import-attribute/master.svg?style=flat-square)](http://travis-ci.org/techdivision/import-attribute)
 [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/techdivision/import-attribute/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/techdivision/import-attribute/?branch=master)
 [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/techdivision/import-attribute/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/techdivision/import-attribute/?branch=master)

This library provides a generic approach to import attributes in an existing Magento 2 CE instance.

## CSV File Structure

By default, the attribute import expects a CSV file with the following defaults

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

| Column Name                   | Type     | Description                                                                           | Example |
|:------------------------------|:---------|---------------------------------------------------------------------------------------|:--------|
| store_view_code               | varchar  | The specific store view(s) for attribute translations. If blank, the row provides the data for the admin store view. | default |
| attribute_set_name            | varchar  | Assigns the attribute to a specific attribute set.                                    | Default |
| attribute_group_name          | varchar  | Assigns the attribute to a specifiv attribute group.                                  | Product Details |
| entity_type_code              | varchar  | The entity type code, the attribute is bound to.                                      | catalog_product |
| attribute_code                | varchar  | The unique attribue code                                                              | my_attribute |
| sort_order                    | int      | The sort order for the attributes in the backend/frontend.                            | 1       |
| attribute_option_values       | text     | A comma (,) separated list with the attribute options and their values.               | option-01,option-02 |
| attribute_option_swatch       | text     | A pipe (|) separated list with the attribute option swatch configuration., if the attribute is swatch type (see additional_data column). | type=2,value=#000000|type=2,value=#d646d6 |
| attribute_option_sort_order   | text     | The sort order for the attribute options in the backend/frontend.                     | 1,2,3   |
| attribute_model               | varchar  | The model to load the EAV data from (**MUST** be the FQCN of a available class).      | Magento\Catalog\Model\ResourceModel\Eav\Attribute |
| backend_model                 | varchar  | The model to conver the user input (**MUST** be the FQCN of a available class).       | Magento\Eav\Model\Entity\Attribute\Backend\Datetime |
| backend_table                 | varchar  | The tabel to store the user input to.                                                 | my_table_name |
| frontend_model                | varchar  | The model to validate the user input (**MUST** be the FQCN of a available class).     | Magento\Eav\Model\Entity\Attribute\Frontend\Datetime |
| frontend_input                | varchar  | The frontend input type (**MUST** be one of hidden, boolean, text, select, date, textarea, text, multiselect, price, weight, media_image or gallery).  | boolean |
| frontend_label                | varchar  | The label for the attribute in the admin store.                                       | My Custom Label |
| frontend_class                | varchar  | A CSS class name.                                                                     | hidden-for-virtual |
| source_model                  | varchar  | The model to load the available values (**MUST** be the FQCN of a available class).   | Magento\Eav\Model\Entity\Attribute\Source\Table |
| frontend_input_renderer       | varchar  | Frontend input renderer (**MUST** be the FQCN of a available class).                  | Magento\Rma\Block\Adminhtml\Product\Renderer |
| apply_to                      | varchar  | A comma (,) separated list with product types, the attribute applies to               | simple,virtual,bundle,downloadable,configurable |
| backend_type                  | varchar  | The attributes backend type (**MUST** be one of text, varchar, decimal, int, datetime or static). | int     |
| is_required                   | int      | Whether or not, the attribute value is mandatory.                                     | 0       |
| is_user_defined               | int      | Whether or not, the attribute is user defined.                                        | 0       |
| default_value                 | text     | The attributes default value (has to depend on the attribute configuration).          | 2       |
| is_unique                     | int      | Whether or not, the attribute **MUST** contain a unique value in the configured context. | 0       |
| note                          | varchar  | A custom note for the attribute (will not be rendered anywhere).                      | My custom note |
| is_global                     | int      | Whether or not, the attribute is global an can be used to create configurable products. | 1       |
| is_visible                    | int      | Whether or not, the attribute is visible in the backend.                              | 1       |
| is_searchable                 | int      | Whether or not, the attribute is searchable in frontend.                              | 0       |
| is_filterable                 | int      | Whether or not, the attribute will be available in the filter naviagtion.             | 0       |
| is_comparable                 | int      | Whether or not, the attribute can be used in product comparison.                      | 0       |
| is_visible_on_front           | int      | Whether or not, the attribute is visible on the catalog pages in store front.         | 0       |
| is_html_allowed_on_front      | int      | Whether or not, the attribute value allows to contain HTML code in store front.       | 0       |
| is_used_for_price_rules       | int      | Whether or not, the attribute can be used to create catalog price rules.              | 0       |
| is_filterable_in_search       | int      | Whether or not, the attribute values can be used to filter the search results.        | 0       |
| used_in_product_listing       | int      | Whether or not, the attribute values will be rendered in the catalog.                 | 0       |
| used_for_sort_by              | int      | Whether or not, the attribute values can be used to sort the catalog.                 | 0       |
| is_visible_in_advanced_search | int      | Whether or not, the attribute is visible in the advanced search                       | 0       |
| position                      | int      | Position of the attribute in layered navigation block.                                | 99      |
| is_wysiwyg_enabled            | int      | Whether or not, the WYSIWYG editor has to be enabled to edit the attribute's value.   | 0       |
| is_used_for_promo_rules       | int      | Whether or not, the attribute can be used to create promo rules.                      | 0       |
| is_required_in_admin_store    | int      | Whether or not, the attribute **MUST** have a value in admin store.                   | 0       |
| is_used_in_grid               | int      | Is used in grid.                                                                      | 0       |
| is_visible_in_grid            | int      | Is visible in grid.                                                                   | 0       |
| is_filterable_in_grid         | int      | Is filterable in grid.                                                                | 0       |
| search_weight                 | float    | Search weight.                                                                        | 1       |
| additional_data               | text     | A comma (,) separated list with dditional attributes, which are necessary to create Visual Swatch or Text Swatch attributes. | swatch_input_type=visual,update_product_preview_image=0,use_product_image_for_swatch=0 |
