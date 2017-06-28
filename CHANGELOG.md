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