<?php

/**
 * TechDivision\Import\Attribute\Callbacks\AttributeRelationFrontendInputTypeToBackendTypeValidatorCallback
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
use TechDivision\Import\Callbacks\IndexedArrayValidatorCallback;
use TechDivision\Import\Loaders\LoaderInterface;
use TechDivision\Import\Subjects\SubjectInterface;
use TechDivision\Import\Utils\RegistryKeys;

/**
 * A callback implementation that validates the a list of attribute set names.
 *
 * @author    Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 * @copyright 2021 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeRelationFrontendInputTypeToBackendTypeValidatorCallback extends IndexedArrayValidatorCallback
{

    /**
     * The array with the values of the main row.
     *
     * @var array
     */
    private $mainRowValues = array();

    /**
     * The main row value loader instance to load the validations with.
     *
     * @var \TechDivision\Import\Loaders\LoaderInterface
     */
    private $mainRowValueLoader;

    /**
     * Initializes the callback with the loader instance.
     *
     * @param \TechDivision\Import\Loaders\LoaderInterface $loader             The loader instance to load the validations with
     * @param \TechDivision\Import\Loaders\LoaderInterface $mainRowValueLoader The loader instance to load the main row values for of the attribute
     * @param boolean                                      $nullable           The flag to decide whether or not the value can be empty
     * @param boolean                                      $mainRowOnly        The flag to decide whether or not the value has to be validated on the main row only
     */
    public function __construct(LoaderInterface $loader, LoaderInterface $mainRowValueLoader, $nullable = false, $mainRowOnly = false)
    {

        // pass the loader to the parent instance
        parent::__construct($loader);

        // the loader for the main row values
        $this->mainRowValueLoader = $mainRowValueLoader;

        // initialize the flags with the passed values
        $this->nullable = $nullable;
        $this->mainRowOnly = $mainRowOnly;
    }

    /**
     * Will be invoked by the callback visitor when a factory has been defined to create the callback instance.
     *
     * @param \TechDivision\Import\Subjects\SubjectInterface $subject The subject instance
     *
     * @return \TechDivision\Import\Callbacks\CallbackInterface The callback instance
     */
    public function createCallback(SubjectInterface $subject)
    {

        // load the main row values as fallback for a store view row
        $this->mainRowValues = $this->mainRowValueLoader->load();

        // return the initialized instance
        return parent::createCallback($subject);
    }

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
                        'Found invalid backend input type "%s" for attribute with code "%s" for frontend Type "%s", must be one of "%s" as backend type',
                        $backendType,
                        $this->getSubject()->getValue(ColumnKeys::ATTRIBUTE_CODE),
                        $this->getValue(ColumnKeys::FRONTEND_INPUT),
                        implode(',', $backendTypeValidation[ColumnKeys::BACKEND_TYPE])
                    );
                    if ($this->getSubject()->isStrictMode()) {
                        // throw an exception if the value is NOT in the array
                        throw new \InvalidArgumentException($message);
                    }
                    $this->getSubject()->getSystemLogger()->warning($message);
                    $this->getSubject()->mergeStatus(
                        array(
                            RegistryKeys::NO_STRICT_VALIDATIONS => array(
                                basename($this->getSubject()->getFilename()) => array(
                                    $this->getSubject()->getLineNumber() => array(
                                        ColumnKeys::BACKEND_TYPE =>  $message
                                    )
                                )
                            )
                        )
                    );
                    return;
                }
            } else {
                continue;
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

    /**
     * Resolve's the value with the passed colum name from the actual row. If the
     * column does not contain a value, the value from the main row, if available
     * will be returned.
     *
     * @param string $name The name of the column to return the value for
     *
     * @return mixed|null The value
     */
    public function getValue($name)
    {

        // load the attribute code of the actual EAV attribute
        $attributeCode = $this->getSubject()->getValue(ColumnKeys::ATTRIBUTE_CODE);

        // load the value of the main row as fallback, if available
        $mainRowValue = isset($this->mainRowValues[$attributeCode]) ? $this->mainRowValues[$attributeCode] : null;

        // return the value of the passed colmn name
        return $this->getSubject()->getValue($name, $mainRowValue);
    }
}
