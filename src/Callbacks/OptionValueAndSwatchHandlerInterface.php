<?php

/**
 * TechDivision\Import\Attribute\Callbacks\OptionValueAndSwatchHandler
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Callbacks;

/**
 * The interface for handler implementations that creates, whether it exists or not, the option as well as the swatch/value.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
interface OptionValueAndSwatchHandlerInterface
{

    /**
     * Queries whether or not an option as well as its swatch/value for the attribute with the given code
     * already exists. If not the option and/or the swatch/value will be created dynamically.
     *
     * @param string  $attributeCode The attribute code to create the option as well as the swatch/value for
     * @param integer $storeId       The store ID
     * @param mixed   $value         The store specific option swatch/value
     *
     * @return void
     */
    public function createOptionValueOrSwatchIfNecessary($attributeCode, $storeId, $value);

    /**
     * Queries whether or not an option as well as its value for the attribute with the given code
     * already exists. If not the option and/or the value will be created dynamically.
     *
     * @param string  $attributeCode The attribute code to create the option as well as the value for
     * @param integer $storeId       The store ID
     * @param mixed   $value         The store specific option swatch/value
     *
     * @return void
     */
    public function createOptionValueIfNecessary($attributeCode, $storeId, $value);

    /**
     * Queries whether or not an option as well as its swatch/value for the attribute with the given code
     * already exists. If not the option and/or the swatch will be created dynamically.
     *
     * @param string  $attributeCode The attribute code to create the option as well as the swatch for
     * @param integer $storeId       The store ID
     * @param mixed   $value         The store specific option swatch/value
     * @param string  $type          The type to create the swatch for
     *
     * @return void
     */
    public function createOptionSwatchIfNecessary($attributeCode, $storeId, $value, $type);
}
