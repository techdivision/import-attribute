<?php

/**
 * TechDivision\Import\Attribute\Subjects\AbstractAttributeSubject
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Subjects;

use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Subjects\AbstractSubject;

/**
 * The abstract product subject implementation that provides basic attribute
 * handling business logic.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
abstract class AbstractAttributeSubject extends AbstractSubject implements AttributeSubjectInterface
{

    /**
     * The array with the available entity types.
     *
     * @var array
     */
    protected $entityTypes = array();

    /**
     * Mappings for attribute code => CSV column header.
     *
     * @var array
     */
    protected $headerMappings = array();

    /**
     * Intializes the previously loaded global data for exactly one bunch.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     * @see \Importer\Csv\Actions\ProductImportAction::prepare()
     */
    public function setUp($serial)
    {

        // load the status of the actual import
        $status = $this->getRegistryProcessor()->getAttribute($serial);

        // load the global data we've prepared initially
        $this->entityTypes = $status[RegistryKeys::GLOBAL_DATA][RegistryKeys::ENTITY_TYPES];

        // prepare the callbacks
        parent::setUp($serial);
    }

    /**
     * Clean up the global data after importing the bunch.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     */
    public function tearDown($serial)
    {

        // invoke the parent method
        parent::tearDown($serial);

        // update the status
        $this->getRegistryProcessor()->mergeAttributesRecursive(
            $serial,
            array(
                RegistryKeys::FILES => array($this->getFilename() => array(RegistryKeys::STATUS => 1))
            )
        );
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
     * Return's the entity type for the passed code.
     *
     * @param string $entityTypeCode The entity type code
     *
     * @return array The requested entity type
     * @throws \Exception Is thrown, if the entity type with the passed code is not available
     */
    public function getEntityType($entityTypeCode)
    {

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
}
