<?php

/**
 * TechDivision\Import\Attribute\Callbacks\FrontendInputAttributeOptionsValidatorCallback
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 * @copyright 2021 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Callbacks;

use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Callbacks\ArrayValidatorCallback;

/**
 * A callback implementation that validates the a list of attribute set names.
 *
 * @author    Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 * @copyright 2021 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionValuesFrontendInputTypeValidatorCallback extends ArrayValidatorCallback
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
        $validations = $this->getValidations();

        // load the frontend input type
        $frontendInputType = $this->getSubject()->getValue(ColumnKeys::FRONTEND_INPUT);

        // query whether or not the column `frontend_input` contains one of the allowed values
        if (in_array($frontendInputType, $validations[$attributeCode])) {
            return;
        }

        // throw an exception if the value is NOT in the array
        throw new \InvalidArgumentException(
            sprintf(
                'Found invalid frontend input type "%s" for attribute code "%s", must be one of "%s" as attribute option values are available',
                $frontendInputType,
                $this->getSubject()->getValue(ColumnKeys::ATTRIBUTE_CODE),
                implode(',', $validations)
            )
        );
    }
}
