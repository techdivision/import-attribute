<?php

/**
 * TechDivision\Import\Attribute\Callbacks\AttributeRelationFrontendInputTypeToBackendTypeValidatorCallback
 *
 * PHP version 7
 *
 * @author    MET <met@techdivision.com>
 * @copyright 2022 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Callbacks;

use TechDivision\Import\Attribute\Utils\ColumnKeys;

/**
 * A callback implementation that validates the a list of attribute set names.
 *
 * @author    MET <met@techdivision.com>
 * @copyright 2022 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeRelationFrontendInputTypeToBackendTypeValidatorCallback extends AbstractAttributeValidatorCallback
{
    /**
     * Will be invoked by the observer it has been registered for.
     *
     * @param string|null $attributeCode  The code of the attribute that has to be validated
     * @param string|null $attributeValue The attribute value to be validated
     *
     * @return mixed The modified value
     * @throws \InvalidArgumentException Is thrown, if the attribute has frontend input values but is not of with backend type from configuration
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

        foreach ($validations as $key => $backendTypeValidation) {
            if ($key === $attributeValue) {
                // query whether or not valid backend type  has
                // been specified in the custom validation configuration
                if (isset($backendTypeValidation[ColumnKeys::BACKEND_TYPE])) {
                    // load the frontend input type
                    $backendType = $this->getValue(ColumnKeys::BACKEND_TYPE);
                    // query whether or not the column `backend_type` contains one of the allowed values
                    if (in_array($backendType, $backendTypeValidation[ColumnKeys::BACKEND_TYPE])) {
                        return;
                    }
                    $message = sprintf(
                        'Found invalid backend_type "%s" for attribute "%s" with frontend Type "%s", must be one of "%s" as backend_type',
                        $backendType,
                        $this->getSubject()->getValue(ColumnKeys::ATTRIBUTE_CODE),
                        $attributeCode,
                        implode(',', $backendTypeValidation[ColumnKeys::BACKEND_TYPE])
                    );

                    // throw an exception if the value is NOT in the array
                    throw new \InvalidArgumentException($message);
                }
            }
        }

        // throw an exception if the necessary configuration is NOT available
        throw new \InvalidArgumentException(
            sprintf(
                'Missing custom validation configuration "backend_type" type within configuration for column "%s"',
                $this->getSubject()->getValue(ColumnKeys::ATTRIBUTE_CODE)
            )
        );
    }
}
