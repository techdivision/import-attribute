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
use TechDivision\Import\Callbacks\IndexedArrayValidatorCallback;
use TechDivision\Import\Loaders\LoaderInterface;
use TechDivision\Import\Subjects\SubjectInterface;

/**
 * A callback implementation that validates the a list of attribute set names.
 *
 * @author    MET <met@techdivision.com>
 * @copyright 2022 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
abstract class AbstractAttributeValidatorCallback extends IndexedArrayValidatorCallback
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
