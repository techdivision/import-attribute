<?php

/**
 * TechDivision\Import\Attribute\Callbacks\AttributeSetNamesValidatorCallback
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Callbacks;

use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Callbacks\IndexedArrayValidatorCallback;

/**
 * A callback implementation that validates the a list of attribute set names.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeSetNamesValidatorCallback extends IndexedArrayValidatorCallback
{

    /**
     * Will be invoked by the observer it has been registered for.
     *
     * @param string|null $attributeCode  The code of the attribute that has to be validated
     * @param string|null $attributeValue The attribute value to be validated
     *
     * @return mixed The modified value
     */
    public function handle($attributeCode = null, $attributeValue = null)
    {

        // explode the values and query whether or not an empty value is allowed
        if ($this->isNullable($values = $this->getSubject()->explode($attributeValue))) {
            return;
        }

        // load the validations for the column
        $validations = $this->getValidations($this->getSubject()->getValue(ColumnKeys::ENTITY_TYPE_CODE));


        if (!is_array($values) || count($values) == 0) {
            // throw an exception if the values is NOT an array
            throw new \InvalidArgumentException(
                sprintf('Found invalid Values "%s" for column "%s"', $values, $attributeCode)
            );
        }

        // iterate over the values and validate them
        foreach ($values as $value) {
            // query whether or not the value is valid
            if (in_array($value, $validations)) {
                continue;
            }

            // throw an exception if the value is NOT in the array
            throw new \InvalidArgumentException(
                sprintf('Found invalid value "%s" for column "%s"', $value, $attributeCode)
            );
        }
    }
}
