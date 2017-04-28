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

namespace TechDivision\Import\Category\Subjects;

use TechDivision\Import\Attribute\Services\AttributeProcessorInterface;

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
abstract class AbstractAttributeSubject extends AbstractEavSubject
{

    /**
     * The processor to read/write the necessary attribute data.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeProcessorInterface
     */
    protected $attributeProcessor;

    /**
     * Initialize the subject instance.
     *
     * @param \TechDivision\Import\Configuration\SubjectConfigurationInterface    $configuration              The subject configuration instance
     * @param \TechDivision\Import\Services\RegistryProcessorInterface            $registryProcessor          The registry processor instance
     * @param \TechDivision\Import\Utils\Generators\GeneratorInterface            $coreConfigDataUidGenerator The UID generator for the core config data
     * @param array                                                               $systemLoggers              The array with the system logger instances
     * @param \TechDivision\Import\Attribute\Services\AttributeProcessorInterface $attributeProcessor         The attribute processor instance
     */
    public function __construct(
        SubjectConfigurationInterface $configuration,
        RegistryProcessorInterface $registryProcessor,
        GeneratorInterface $coreConfigDataUidGenerator,
        array $systemLoggers,
        AttributeProcessorInterface $attributeProcessor
    ) {

        // pass the arguments to the parent constructor
        parent::__construct($configuration, $registryProcessor, $coreConfigDataUidGenerator, $systemLoggers);

        // initialize the attribute processor
        $this->attributeProcessor= $attributeProcessor;
    }
}
