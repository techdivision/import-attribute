# Version 22.0.0

## Bugfixes

* PAC-328: Missing specification of attribute_set_name and attribute_set_group leads to PHP warning

## Features

* Refactoring deprecated classes. see https://github.com/techdivision/import-cli-simple/blob/master/UPGRADE-4.0.0.md
* Add #PAC-292: Validate attibute code when importing EAV attributes
* PAC-96: Use new constands for FileUploadConfiguration
    * https://github.com/techdivision/import/issues/181
* PAC-361: Don't check file system if copy-images defined as false
    * https://github.com/techdivision/import-cli-simple/issues/262
* PAC-324: Append empty value validator for attribut_option_values
* New validator callbacks
    * Relation between fontend_input and backent_type
    * Improve regex for attriute code like Magento allowed

# Version 21.0.0

## Bugfixes

* None

## Features

* Add #PAC-227: Dynamic Handling of columns for import of EAV attributes

# Version 20.1.3

## Bugfixes

* Fixed #PAC-243: Fixed issue of attributes that can not be updated

## Features

* Replace warning with stack trace of exception for missing media directories with debug level

# Version 20.1.2

## Bugfixes

* None

## Features

* Use only log message instead of stack trace for missing media directory

# Version 20.1.1

## Bugfixes

* None

## Features

* Fixed techdivision/import-attribute#52

# Version 20.1.0

## Bugfixes

* None

## Features

* Fixed techdivision/import-attribute#51

# Version 20.0.0

## Bugfixes

* None

## Features

* Add #PAC-47: Add entity merger implementation to allow fine grained entity merging

# Version 19.2.0

## Bugfixes

* None

## Features

* Add #PAC-96

# Version 19.1.2

## Bugfixes

* None

## Features

* Remove unneccessary clean-up-empty-columns array from configuration of replace operation

# Version 19.1.1

## Bugfixes

* Fixed invalid Symfony DI configuration for SQL compilers

## Features

* None

# Version 19.1.0

## Bugfixes

* None

## Features

* Replace default SQL statement compiler with dynamic compiler implementation

# Version 19.0.0

## Bugfixes

* None

## Features

* Add #PAC-47

# Version 18.0.0

## Bugfixes

* None

## Features

* Add #PAC-85
* Add techdivision/import-cli-simple#244
* Add techdivision/import-attribute#46 (Possibility to import files for option swatch)

# Version 17.0.3

## Bugfixes

* None

## Features

* Remove extract dev autoloading

# Version 17.0.2

## Bugfixes

* None

## Features

* Extract dev autoloading

# Version 17.0.1

## Bugfixes

* Fixed techdivision/import-attribute#47

## Features

* None

# Version 17.0.0

## Bugfixes

* None

## Features

* Remove deprecated classes and methods
* Add techdivision/import#162
* Add techdivision/import-cli-simple#216
* Add techdivision/import-configuration-jms#25
* Remove unnecessary identifiers from configuration
* Switch to latest techdivision/import 15.* version as dependency

# Version 16.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 14.* version as dependency

# Version 15.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 13.* version as dependency

# Version 14.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 12.* version as dependency

# Version 13.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 11.* version as dependency

# Version 12.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 10.0.* version as dependency

# Version 11.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 9.0.* version as dependency

# Version 10.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 8.0.* version as dependency

# Version 9.0.2

## Bugfixes

* Add missing file-resolver configuration node

## Features

* None

# Version 9.0.1

## Bugfixes

* Update default configuration files with listeners

## Features

* None

# Version 9.0.0

## Bugfixes

* None

## Features

* Make Actions and ActionInterfaces deprecated, replace DI configuration with GenericAction + GenericIdentifierAction

# Version 8.0.0

## Bugfixes

* None

## Features

* Add composite observers to minimize configuration complexity
* Switch to latest techdivision/import 7.0.* version as dependency

# Version 7.0.1

## Bugfixes

* Fixed #41

## Features

* None

# Version 7.0.0

## Bugfixes

* Change method visibility EntityAttributeUpdateObserver::loadEntityAttributeByAttributeIdAndAttributeSetId() from public to protected

## Features

* None

# Version 6.0.0

## Bugfixes

* Fixed invalid of update functionality when assigning attribute groups

## Features

* None

# Version 5.0.0

## Bugfixes

* Fixed invalid assignment of attributes to multiple attribute sets/groups

## Features

* Add missing sort order when dynamically creating attribute options
* Switch to latest techdivision/import 6.0.* version as dependency

# Version 4.0.0

## Bugfixes

* Switch to latest techdivision/import 5.0.* version as dependency
* Fixed invalid loader functionality for attributes by adding entity_type_id parameter to methods and SQLs

## Features

* Refactoring callbacks integration of version 3.1.0

# Version 3.1.0

## Bugfixes

* None

## Features

* Add callbacks to dynamically create attribute option as well as option values/swatches (merged from 2.x branch)

# Version 3.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import ~4.0 version as dependency

# Version 2.0.0

## Bugfixes

* None

## Features

* Compatibility for Magento 2.2.x

# Version 1.0.2

## Bugfixes

* None

## Features

* Also allow techdivision/import ~2.0 versions as dependency

# Version 1.0.1

## Bugfixes

* Switch to phpdocumentor v2.9.* to avoid Travis-CI build errors

## Features

* None

# Version 1.0.0

## Bugfixes

* None

## Features

* Move PHPUnit test from tests to tests/unit folder for integration test compatibility reasons

# Version 1.0.0-beta25

## Bugfixes

* None

## Features

* Remove unnecessary methods from AttributeBunchProcessorInterface

# Version 1.0.0-beta24

## Bugfixes

* None

## Features

* Add missing interfaces for actions and repositories
* Replace class type hints for AttributeBunchProcessor with interfaces

# Version 1.0.0-beta23

## Bugfixes

* None

## Features

* Configure DI to passe event emitter to subjects constructor

# Version 1.0.0-beta22

## Bugfixes

* None

## Features

* Refactored DI + switch to new SqlStatementRepositories instead of SqlStatements

# Version 1.0.0-beta21

## Bugfixes

* None

## Features

* Remove unnecessary AbstractAttributeSubject::tearDown() method

# Version 1.0.0-beta20

## Bugfixes

* None

## Features

* Refactoring to optimize artefact export handling

# Version 1.0.0-beta19

## Bugfixes

* Fix issue for updating attribute option swatch values

## Features

* None

# Version 1.0.0-beta18

## Bugfixes

* Fixes attribute import issue for option translations

## Features

* None

# Version 1.0.0-beta17

## Bugfixes

* None

## Features

* Make sure that the size of option values equlas the size of swatch values when a swatch type is found

# Version 1.0.0-beta16

## Bugfixes

* Remove unnecessary error_log() statements

## Features

* None

# Version 1.0.0-beta15

## Bugfixes

* None

## Features

* Insert/Update values in catalog_eav_attribute table dynamically

# Version 1.0.0-beta14

## Bugfixes

* None

## Features

* Add custom system logger to default configuration

# Version 1.0.0-beta13

## Bugfixes

* Remove invalid attribute option pre-load observer in attribute replace operation

## Features

* None

# Version 1.0.0-beta12

## Bugfixes

* Revert fixes from 1.0.0-beta11

## Features

* Use EntitySubjectInterface for entity related subjects

# Version 1.0.0-beta11

## Bugfixes

* Fix issue which caused, that all attribute settings were overriten by default values

## Features

* None

# Version 1.0.0-beta10

## Bugfixes

* None

## Features

* Refactor to optimize DI integration

# Version 1.0.0-beta9

## Bugfixes

* None

## Features

* Switch to new plugin + subject factory implementations

# Version 1.0.0-beta8

## Bugfixes

* Fix issue which caused multiple attribute option values for same option_id and store_id

## Features

* None

# Version 1.0.0-beta7

## Bugfixes

* Fix json syntax errors

## Features

* None

# Version 1.0.0-beta6

## Bugfixes

* Remove clean-up observer for option and option-value import

## Features

* None

# Version 1.0.0-beta5

## Bugfixes

* None

## Features

* Use Robo for Travis-CI build process 
* Refactoring for new ConnectionInterface + SqlStatementsInterface

# Version 1.0.0-beta4

## Bugfixes

* Define selected fields when loading rows from catalog_eav_attribute to avoid exception on custom defined fields

## Features

* Remove archive directory from default configuration file

# Version 1.0.0-beta3

## Bugfixes

* None

## Features

* Bugfix for invalid query on 0 values when creating text swatch values

# Version 1.0.0-beta2

## Bugfixes

* None

## Features

* Refactoring Symfony DI integration

# Version 1.0.0-beta1

## Bugfixes

* None

## Features

* Replace AttributeOptionValueRepository with EavAttributeOptionValueRepository from techdivision/import library

# Version 1.0.0-alpha6

## Bugfixes

* Fixed invalid usage of passing by reference in AttributeOptionValueExportObserver class

## Features

* None

# Version 1.0.0-alpha5

## Bugfixes

* Load attribute sort_order column from CSV file instead of setting 0
* Update README.md file

## Features

* None

# Version 1.0.0-alpha4

## Bugfixes

* None

## Features

* Update generic configuration file
* Add functionality to persist attribute labels
* Add functionality to persist entity attribute data
* Implement EAV attribute option swatch delete/add-update + replace functionality
* Add handling for additional_data attribute => necessary to create swatch attributes

# Version 1.0.0-alpha3

## Bugfixes

* None

## Features

* Implement functionality to persist EAV attribute options + labels (no update)

# Version 1.0.0-alpha2

## Bugfixes

* None

## Features

* Implement functionality to persist EAV attribute without labels + options

# Version 1.0.0-alpha1

## Bugfixes

* None

## Features

* Initial Release
