<?php

/**
 * TechDivision\Import\Attribute\Subjects\AbstractAttributeSubject
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Subjects;

use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Subjects\AbstractSubject;
use TechDivision\Import\Subjects\EntitySubjectInterface;
use TechDivision\Import\Subjects\CleanUpColumnsSubjectInterface;
use TechDivision\Import\Attribute\Utils\ConfigurationKeys;

/**
 * The abstract product subject implementation that provides basic attribute
 * handling business logic.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
abstract class AbstractAttributeSubject extends AbstractSubject implements AttributeSubjectInterface, EntitySubjectInterface, CleanUpColumnsSubjectInterface
{

    /**
     * The array with the available entity types.
     *
     * @var array
     */
    protected $entityTypes = array();

    /**
     * The default entity type code.
     *
     * @var string
     */
    protected $defaultEntityTypeCode;

    /**
     * Intializes the previously loaded global data for exactly one bunch.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     */
    public function setUp($serial)
    {

        // load the status of the actual import
        $status = $this->getRegistryProcessor()->getAttribute(RegistryKeys::STATUS);

        // load the global data we've prepared initially
        $this->entityTypes = $status[RegistryKeys::GLOBAL_DATA][RegistryKeys::ENTITY_TYPES];

        // initialize the default entity type code with the value from the configuration
        $this->defaultEntityTypeCode = $this->getEntityTypeCode();

        // prepare the callbacks
        parent::setUp($serial);
    }

    /**
     * Return's the header mappings for the actual entity.
     *
     * @return array The header mappings
     */
    public function getHeaderMappings()
    {
        return $this->headerMappings;
    }

    /**
     * Returns the default entity type code.
     *
     * @return string The default entity type code
     */
    public function getDefaultEntityTypeCode()
    {
        return $this->defaultEntityTypeCode;
    }

    /**
     * Return's the entity type for the passed code, of if no entity type code has
     * been passed, the default one from the configuration will be used.
     *
     * @param string|null $entityTypeCode The entity type code
     *
     * @return array The requested entity type
     * @throws \Exception Is thrown, if the entity type with the passed code is not available
     */
    public function getEntityType($entityTypeCode = null)
    {

        // set the default entity type code, if non has been passed
        if ($entityTypeCode === null) {
            $entityTypeCode = $this->getDefaultEntityTypeCode();
        }

        // query whether or not, the entity type with the passed code is available
        if (isset($this->entityTypes[$entityTypeCode])) {
            return $this->entityTypes[$entityTypeCode];
        }

        // throw an exception, if not
        throw new \Exception(
            sprintf(
                'Can\'t find entity type with code %s in file %s on line %d',
                $entityTypeCode,
                $this->getFilename(),
                $this->getLineNumber()
            )
        );
    }

    /**
     * Returns the entity type ID for the passed code, or if no entity type code has
     * been passed, the default one from the configuration will be used.
     *
     * @param string|null $entityTypeCode The entity type code
     *
     * @return integer The actual entity type ID
     */
    public function getEntityTypeId($entityTypeCode = null)
    {

        // load the entity type for the given code, or the default one otherwise
        $entityType = $this->getEntityType($entityTypeCode ? $entityTypeCode : $this->getDefaultEntityTypeCode());

        // return the entity type ID
        return $entityType[MemberNames::ENTITY_TYPE_ID];
    }

    /**
     * Merge the columns from the configuration with all image type columns to define which
     * columns should be cleaned-up.
     *
     * @return array The columns that has to be cleaned-up
     */
    public function getCleanUpColumns()
    {

        // initialize the array for the columns that has to be cleaned-up
        $cleanUpColumns = array();

        // query whether or not an array has been specified in the configuration
        if ($this->getConfiguration()->hasParam(ConfigurationKeys::CLEAN_UP_EMPTY_COLUMNS)) {
            $cleanUpColumns = $this->getConfiguration()->getParam(ConfigurationKeys::CLEAN_UP_EMPTY_COLUMNS);
        }

        // return the array with the column names
        return $cleanUpColumns;
    }
}
