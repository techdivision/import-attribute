<?php

/**
 * TechDivision\Import\Attribute\Subjects\BunchSubject
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

use TechDivision\Import\Subjects\ExportableTrait;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\RegistryKeys;
use TechDivision\Import\Subjects\ExportableSubjectInterface;
use TechDivision\Import\Utils\Generators\GeneratorInterface;
use TechDivision\Import\Services\RegistryProcessorInterface;
use TechDivision\Import\Configuration\SubjectConfigurationInterface;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * The subject implementation that handles the business logic to persist attributes.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class BunchSubject extends AbstractAttributeSubject implements ExportableSubjectInterface
{

    /**
     * The trait that implements the export functionality.
     *
     * @var \TechDivision\Import\Subjects\ExportableTrait;
     */
    use ExportableTrait;

    /**
     * The attribute processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

    /**
     * The ID of the attribute that has been created recently.
     *
     * @var integer
     */
    protected $lastAttributeId;

    /**
     * The array with the pre-loaded attribute IDs.
     *
     * @var array
     */
    protected $preLoadedAttributeIds = array();

    /**
     * The attribute code => attribute ID mapping.
     *
     * @var array
     */
    protected $attributeCodeIdMapping = array();

    /**
     * Initialize the subject instance.
     *
     * @param \TechDivision\Import\Configuration\SubjectConfigurationInterface         $configuration              The subject configuration instance
     * @param \TechDivision\Import\Services\RegistryProcessorInterface                 $registryProcessor          The registry processor instance
     * @param \TechDivision\Import\Utils\Generators\GeneratorInterface                 $coreConfigDataUidGenerator The UID generator for the core config data
     * @param array                                                                    $systemLoggers              The array with the system loggers instances
     * @param \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface $attributeBunchProcessor    The attribute bunch processor instance
     */
    public function __construct(
        SubjectConfigurationInterface $configuration,
        RegistryProcessorInterface $registryProcessor,
        GeneratorInterface $coreConfigDataUidGenerator,
        array $systemLoggers,
        AttributeBunchProcessorInterface $attributeBunchProcessor
    ) {

        // pass the parameters to the parent constructor
        parent::__construct($configuration, $registryProcessor, $coreConfigDataUidGenerator, $systemLoggers);

        // initialize the attribute bunch processor
        $this->attributeBunchProcessor = $attributeBunchProcessor;
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

        // load the registry processor
        $registryProcessor = $this->getRegistryProcessor();

        // update the status
        $registryProcessor->mergeAttributesRecursive(
            $serial,
            array(
                RegistryKeys::PRE_LOADED_ATTRIBUTE_IDS => $this->preLoadedAttributeIds,
            )
        );
    }

    /**
     * Pre-load the attribute ID for the EAV attribute with the passed code.
     *
     * @param string $attributeCode The code of the EAV attribute to pre-load
     *
     * @return void
     */
    public function preLoadAttributeId($attributeCode)
    {

        // load the EAV attribute by the passed code
        $attribute = $this->loadAttributeByAttributeCode($attributeCode);

        // temporary persist the pre-loaded attribute code => ID mapping
        $this->preLoadedAttributeIds[$attributeCode]= $attribute[MemberNames::ATTRIBUTE_ID];
    }

    /**
     * Return's the ID of the attribute that has been created recently.
     *
     * @return integer The attribute ID
     */
    public function getLastEntityId()
    {
        return $this->getLastAttributeId();
    }

    /**
     * Set's the ID of the attribute that has been created recently.
     *
     * @param integer $lastAttributeId The attribute ID
     *
     * @return void
     */
    public function setLastAttributeId($lastAttributeId)
    {
        $this->lastAttributeId = $lastAttributeId;
    }

    /**
     * Return's the ID of the attribute that has been created recently.
     *
     * @return integer The attribute ID
     */
    public function getLastAttributeId()
    {
        return $this->lastAttributeId;
    }

    /**
     * Return's the attribute bunch processor instance.
     *
     * @return \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface The attribute bunch processor instance
     */
    protected function getAttributeBunchProcessor()
    {
        return $this->attributeBunchProcessor;
    }

    /**
     * Load's and return's the EAV attribute with the passed code.
     *
     * @param string $attributeCode The code of the EAV attribute to load
     *
     * @return array The EAV attribute
     */
    protected function loadAttributeByAttributeCode($attributeCode)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeByAttributeCode($attributeCode);
    }
}
