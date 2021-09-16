<?php

/**
 * TechDivision\Import\Attribute\Callbacks\AttributeGroupNamesValidatorCallback
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Callbacks;

use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Callbacks\IndexedArrayValidatorCallback;

/**
 * A callback implementation that validates the attribute group names in an attribute import.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product
 * @link      http://www.techdivision.com
 */
class AttributeGroupNamesValidatorCallback extends IndexedArrayValidatorCallback
{

    /**
     * Returns the validations for the attribute with the passed code.
     *
     * @param string|null $attributeCode  The code of the attribute to return the validations for
     * @param string|null $entityTypeCode The entity type code used to return the validations
     *
     * @return array The allowed values for the attribute with the passed code
     */
    protected function getValidations($attributeCode = null, $entityTypeCode = null)
    {

        // load the validations for the given entity type code
        $validations = parent::getValidations($entityTypeCode);

        // query whether or not validations for the passed attribute code has been specified
        if (isset($validations[$attributeCode])) {
            return $validations[$attributeCode];
        }

        // return an empty array, if NOT
        return array();
    }

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

        // load the subject instance
        $subject = $this->getSubject();

        // explode the attribute group names
        if ($this->isNullable($attributeGroupNames = $subject->explode($attributeValue))) {
            return;
        }

        // load the attribute set names of attribute groups that has to be validated
        $attributeSetNames = $subject->getValue(ColumnKeys::ATTRIBUTE_SET_NAME, array(), array($subject, 'explode'));

        // iterate over the attribute set names to load the available attribute group names therefore
        foreach ($attributeSetNames as $key => $attributeSetName) {
            // load the validations for the attribute set with the given name
            $validations = $this->getValidations($attributeSetName, $subject->getValue(ColumnKeys::ENTITY_TYPE_CODE));

            // query whether or not the value is valid
            if (isset($attributeGroupNames[$key]) && in_array($attributeGroupName = $attributeGroupNames[$key], $validations)) {
                continue;
            }

            // throw an exception if the value is NOT in the array
            throw new \InvalidArgumentException(
                sprintf('Found invalid attribute group name "%s"', $attributeGroupName)
            );
        }
    }
}
