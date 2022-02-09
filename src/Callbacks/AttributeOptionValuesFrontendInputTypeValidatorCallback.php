<?php

/**
 * TechDivision\Import\Attribute\Callbacks\AttributeOptionValuesFrontendInputTypeValidatorCallback
 *
 * PHP version 7
 *
 * @author    Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 * @copyright 2021 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Callbacks;

use TechDivision\Import\Attribute\Utils\ColumnKeys;

/**
 * A callback implementation that validates the a list of attribute set names.
 *
 * @author    Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 * @copyright 2021 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionValuesFrontendInputTypeValidatorCallback extends AbstractAttributeValidatorCallback
{

    /**
     * Will be invoked by the observer it has been registered for.
     *
     * @param string|null $attributeCode  The code of the attribute that has to be validated
     * @param string|null $attributeValue The attribute value to be validated
     *
     * @return mixed The modified value
     * @throws \InvalidArgumentException Is thrown, if the attribute has option values but is not of frontend input type `select` or `multiselect`
     */
    public function handle($attributeCode = null, $attributeValue = null)
    {
        // query whether or not the passed value
        // IS empty and empty values are allowed
        if ($this->isNullable($attributeValue)) {
            return;
        }

        // the validations for the attribute with the given code
        $validations = $this->getValidations($attributeCode);

        // query whether or not valid frontend input types has
        // been specified in the custom validation configuration
        if (isset($validations[ColumnKeys::FRONTEND_INPUT])) {
            // load the frontend input type
            $frontendInputType = $this->getValue(ColumnKeys::FRONTEND_INPUT);
            // query whether or not the column `frontend_input` contains one of the allowed values
            if (in_array($frontendInputType, $validations[ColumnKeys::FRONTEND_INPUT])) {
                return;
            }

            // throw an exception if the value is NOT in the array
            throw new \InvalidArgumentException(
                sprintf(
                    'Found invalid frontend input type "%s" for attribute with code "%s", must be one of "%s" as attribute option values are available',
                    $frontendInputType,
                    $this->getSubject()->getValue(ColumnKeys::ATTRIBUTE_CODE),
                    implode(',', $validations[ColumnKeys::FRONTEND_INPUT])
                )
            );
        }

        // throw an exception if the necessary configuration is NOT available
        throw new \InvalidArgumentException(
            sprintf(
                'Missing custom validation configuration "frontend_input" type within configuration for column "%s"',
                $this->getSubject()->getValue(ColumnKeys::ATTRIBUTE_CODE)
            )
        );
    }
}
