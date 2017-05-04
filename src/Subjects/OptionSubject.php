<?php

/**
 * TechDivision\Import\Attribute\Subjects\OptionSubject
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

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Utils\Generators\GeneratorInterface;
use TechDivision\Import\Services\RegistryProcessorInterface;
use TechDivision\Import\Configuration\SubjectConfigurationInterface;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * The subject implementation that handles the business logic to persist attribute options.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class OptionSubject extends AbstractAttributeSubject
{

    /**
     * The attribute processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

    /**
     * The ID of the option that has been created recently.
     *
     * @var integer
     */
    protected $lastOptionId;

    /**
     * The value => option ID mapping.
     *
     * @var array
     */
    protected $attributeCodeValueOptionIdMapping = array();

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
     * Return's the attribute bunch processor instance.
     *
     * @return \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface The attribute bunch processor instance
     */
    protected function getAttributeBunchProcessor()
    {
        return $this->attributeBunchProcessor;
    }

    /**
     * Map's the passed attribue code and value to the option ID that has been created recently.
     *
     * @param string $attributeCode The attriburte code that has to be mapped
     * @param string $value         The value that has to be mapped
     *
     * @return void
     */
    public function addAddtributeCodeValueOptionIdMapping($attributeCode, $value)
    {
        $this->attributeCodeValueOptionIdMapping[$attributeCode][$value] = $this->getLastEntityId();
    }

    /**
     * Queries whether or not the attribute with the passed code/value has already been processed.
     *
     * @param string $attributeCode The attribute code to check
     * @param string $value         The option value to check
     *
     * @return boolean TRUE if the path has been processed, else FALSE
     */
    public function hasBeenProcessed($attributeCode, $value)
    {
        return isset($this->attributeCodeValueOptionIdMapping[$attributeCode][$value]);
    }

    /**
     * Return's the ID of the attribute that has been created recently.
     *
     * @return integer The attribute ID
     */
    public function getLastEntityId()
    {
        return $this->getLastOptionId();
    }

    /**
     * Set's the ID of the option that has been created recently.
     *
     * @param integer $lastOptionId The option ID
     *
     * @return void
     */
    public function setLastOptionId($lastOptionId)
    {
        $this->lastOptionId = $lastOptionId;
    }

    /**
     * Return's the ID of the option that has been created recently.
     *
     * @return integer The option ID
     */
    public function getLastOptionId()
    {
        return $this->lastOptionId;
    }

    /**
     * Pre-load the option ID for the EAV attribute option with the passed attribute code/value.
     *
     * @param string $attributeCode The code of the EAV attribute to pre-load
     * @param string $value         The option admin store view value of the EAV attribute option to pre-load
     *
     * @return void
     */
    public function preLoadOptionId($attributeCode, $value)
    {

        // load the ID of the admin store
        $storeId = $this->stores[StoreViewCodes::ADMIN][MemberNames::STORE_ID];

        // load the EAV attribute option with the passed value
        $attributeOption = $this->getAttributeBunchProcessor()->loadAttributeOptionByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value);

        // set the EAV attribute option ID
        $this->setLastOptionId($attributeOption[MemberNames::OPTION_ID]);
    }
}
