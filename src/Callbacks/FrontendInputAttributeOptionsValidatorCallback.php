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
use TechDivision\Import\Callbacks\AbstractValidatorCallback;
use TechDivision\Import\Utils\FrontendInputTypes;

/**
 * A callback implementation that validates the a list of attribute set names.
 *
 * @author    Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 * @copyright 2021 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class FrontendInputAttributeOptionsValidatorCallback extends AbstractValidatorCallback
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

        // load the atribute options
        $optionsValues = $this->getSubject()
            ->getValue(ColumnKeys::ATTRIBUTE_OPTION_VALUES, null, array($this->getSubject(), 'explode'));

        // query whether or not the passed value IS empty
        if ($optionsValues === '' || $optionsValues === null) {
            return;
        }

        if (!in_array($attributeValue, [FrontendInputTypes::SELECT, FrontendInputTypes::MULTISELECT])) {
            // throw an exception if the value is NOT in the array
            throw new \InvalidArgumentException(
                sprintf(
                    'Found invalid value "%s" for attribute code "%s". "select" or "multiselect" required',
                    $attributeValue,
                    $this->getSubject()->getValue(ColumnKeys::ATTRIBUTE_CODE)
                )
            );
        }
    }
}
