<?php

/**
 * TechDivision\Import\Attribute\Callbacks\OptionValueAndSwatchHandler
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

namespace TechDivision\Import\Attribute\Callbacks;

use Doctrine\Common\Collections\Collection;
use TechDivision\Import\SystemLoggerTrait;
use TechDivision\Import\Utils\EntityStatus;
use TechDivision\Import\ConfigurationInterface;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * A handler implementation that creates, whether it exists or not, the option as well as the swatch/value.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class OptionValueAndSwatchHandler implements OptionValueAndSwatchHandlerInterface
{

    /**
     * The trait that provides system logger functionality.
     *
     * @var \TechDivision\Import\SystemLoggerTrait
     */
    use SystemLoggerTrait;

    /**
     * The swatch type loader instance.
     *
     * @var \TechDivision\Import\Attribute\Callbacks\SwatchTypeLoaderInterface
     */
    protected $swatchTypeLoader;

    /**
     * The attribute bunch processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

    /**
     * The configuration instance.
     *
     * @var \TechDivision\Import\ConfigurationInterface
     */
    protected $configuration;

    /**
     * Initialize the handler with the passed logger collection, swatch type loader and attribute bunch processor instance.
     *
     * @param \TechDivision\Import\ConfigurationInterface                              $configuration      The configuration instance
     * @param \Doctrine\Common\Collections\Collection                                  $systemLoggers      The collection containing the system loggers
     * @param \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface $attributeProcessor The attribute bunch processor instance
     * @param \TechDivision\Import\Attribute\Callbacks\SwatchTypeLoaderInterface       $swatchTypeLoader   The swatch type loader instance
     */
    public function __construct(
        ConfigurationInterface $configuration,
        Collection $systemLoggers,
        AttributeBunchProcessorInterface $attributeProcessor,
        SwatchTypeLoaderInterface $swatchTypeLoader
    ) {
        $this->configuration = $configuration;
        $this->systemLoggers = $systemLoggers;
        $this->swatchTypeLoader = $swatchTypeLoader;
        $this->attributeBunchProcessor = $attributeProcessor;
    }

    /**
     * Returns the swatch type loader instance.
     *
     * @return \\TechDivision\Import\Attribute\Callbacks\SwatchTypeLoaderInterface The swatch type loader instance
     */
    protected function getSwatchTypeLoader()
    {
        return $this->swatchTypeLoader;
    }

    /**
     * Returns the attribute bunch processor instance.
     *
     * @return \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface The attribute bunch processor instance
     */
    protected function getAttributeBunchProcessor()
    {
        return $this->attributeBunchProcessor;
    }

    /**
     * Returns the configuration instance.
     *
     * @return \TechDivision\Import\ConfigurationInterface The configuration instance
     */
    protected function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Queries whether or not an option as well as its swatch/value for the attribute with the given code
     * already exists. If not the option and/or the swatch/value will be created dynamically.
     *
     * @param string  $attributeCode The attribute code to create the option as well as the swatch/value for
     * @param integer $storeId       The store ID
     * @param mixed   $value         The store specific option swatch/value
     *
     * @return void
     */
    public function createOptionValueOrSwatchIfNecessary($attributeCode, $storeId, $value)
    {

        // queryh whether or not we've a swatch attribute
        if ($type = $this->getSwatchTypeLoader()->loadSwatchType($this->getEntityTypeId(), $attributeCode)) {
            $this->createOptionSwatchIfNecessary($attributeCode, $storeId, $value, $type);
        } else {
            $this->createOptionValueIfNecessary($attributeCode, $storeId, $value);
        }
    }

    /**
     * Queries whether or not an option as well as its value for the attribute with the given code
     * already exists. If not the option and/or the value will be created dynamically.
     *
     * @param string  $attributeCode The attribute code to create the option as well as the value for
     * @param integer $storeId       The store ID
     * @param mixed   $value         The store specific option swatch/value
     *
     * @return void
     */
    public function createOptionValueIfNecessary($attributeCode, $storeId, $value)
    {

        // try to load the attribute option value and add the option ID
        if ($this->loadAttributeOptionValueByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId = $this->getEntityTypeId(), $attributeCode, $storeId, $value)) {
            return;
        }

        // initialize the option ID
        $optionId = $this->createOptionByValueIfNecessary($entityTypeId, $attributeCode, $storeId, $value);

        // create the value for the option
        $this->persistAttributeOptionValue($this->prepareAttributeOptionValue($optionId, $storeId, $value));

        // log a message that the value has successfully been created
        $this->getSystemLogger()->info(sprintf('Successfully created option value for attribute with code "%s", store ID "%d" and value "%s"', $attributeCode, $storeId, $value));
    }

    /**
     * Queries whether or not an option as well as its swatch/value for the attribute with the given code
     * already exists. If not the option and/or the swatch will be created dynamically.
     *
     * @param string  $attributeCode The attribute code to create the option as well as the swatch for
     * @param integer $storeId       The store ID
     * @param mixed   $value         The store specific option swatch/value
     * @param string  $type          The type to create the swatch for
     *
     * @return void
     */
    public function createOptionSwatchIfNecessary($attributeCode, $storeId, $value, $type)
    {

        // try to load the attribute option value and add the option ID
        if ($this->loadAttributeOptionSwatchByEntityTypeIdAndAttributeCodeAndStoreIdAndValueAndType($entityTypeId = $this->getEntityTypeId(), $attributeCode, $attributeCode, $storeId, $value)) {
            return;
        }

        // initialize the option ID
        $optionId = $this->createOptionBySwatchIfNecessary($entityTypeId, $attributeCode, $storeId, $value, $type);

        // create the swatch for the option
        $this->persistAttributeOptionSwatch($this->prepareAttributeOptionSwatch($optionId, $storeId, $value, $type));

        // log a message that the swwatch has successfully been created
        $this->getSystemLogger()->info(sprintf('Successfully created option swatch for attribute with code "%s", store ID "%d" and value "%s"', $attributeCode, $storeId, $value));
    }

    /**
     * Returns the configured entity type code.
     *
     * @return string The entity type code from the configuration
     */
    protected function getEntityTypeCode()
    {
        return $this->getConfiguration()->getEntityTypeCode();
    }

    /**
     * Returns the entity type ID for the configured entity type code.
     *
     * @return integer The entity type ID of the configured entity type
     */
    protected function getEntityTypeId()
    {

        // load the entity type for the configured entity type code
        $entityType = $this->getAttributeBunchProcessor()->loadEntityTypeByEntityTypeCode($this->getEntityTypeCode());

        // return the entity type ID
        return $entityType[MemberNames::ENTITY_TYPE_ID];
    }

    /**
     * Queries whether or not an option for the attribute with the given code already exists.
     * If not the option and/or the swatch/value will be created dynamically.
     *
     * @param integer $entityTypeId  The entity type ID of the option to create
     * @param string  $attributeCode The attribute code to create the option as well as the swatch/value for
     * @param integer $storeId       The store ID
     * @param mixed   $value         The store specific option swatch/value
     *
     * @return integer The ID of the option
     */
    protected function createOptionByValueIfNecessary($entityTypeId, $attributeCode, $storeId, $value)
    {

        // initialize the option ID
        $optionId = 0;

        // try to load the attribute option by the given attribute code, store ID + value
        if ($attributeOption = $this->loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value)) {
            // set the ID of the loaded option
            $optionId = $attributeOption[MemberNames::OPTION_ID];
        } else {
            // create a new option and set the ID
            $optionId = $this->persistAttributeOption($this->prepareAttributeOption($entityTypeId, $attributeCode));
            // log a message that a new option has been created
            $this->getSystemLogger()->info(sprintf('Successfully created new option for attribute with code "%s"', $attributeCode));
        }

        // return the option ID
        return $optionId;
    }

    /**
     * Queries whether or not an option for the attribute with the given code already exists.
     * If not the option and/or the swatch/value will be created dynamically.
     *
     * @param integer $entityTypeId  The entity type ID of the option to create
     * @param string  $attributeCode The attribute code to create the option as well as the swatch/value for
     * @param integer $storeId       The store ID
     * @param mixed   $value         The store specific option swatch/value
     * @param string  $type          The type of the swatch
     *
     * @return integer The ID of the option
     */
    protected function createOptionBySwatchIfNecessary($entityTypeId, $attributeCode, $storeId, $value, $type)
    {

        // initialize the option ID
        $optionId = 0;

        // try to load the attribute option by the given attribute code, store ID + value
        if ($attributeOption = $this->loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndSwatchAndType($entityTypeId, $attributeCode, $storeId, $value, $type)) {
            // set the ID of the loaded option
            $optionId = $attributeOption[MemberNames::OPTION_ID];
        } else {
            // create a new option and set the ID
            $optionId = $this->persistAttributeOption($this->prepareAttributeOption($attributeCode));
            // log a message that a new option has been created
            $this->getSystemLogger()->info(sprintf('Successfully created new option for attribute with code "%s"', $attributeCode));
        }

        // return the option ID
        return $optionId;
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @param integer $entityTypeId  The entity type ID of the option
     * @param string  $attributeCode The attribute code of the option
     *
     * @return array The prepared option
     */
    protected function prepareAttributeOption($entityTypeId, $attributeCode)
    {

        // load the attribute ID
        $attribute = $this->loadAttributeByEntityTypeIdAndAttributeCode($entityTypeId, $attributeCode);

        // return the prepared attribute option
        return $this->initializeEntity(
            array(
                MemberNames::ATTRIBUTE_ID  => $attribute[MemberNames::ATTRIBUTE_ID],
                MemberNames::SORT_ORDER    => 0
            )
        );
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @param integer $optionId The option ID of the option value to create
     * @param integer $storeId  The store ID to create the option value for
     * @param mixed   $value    The option value itself
     *
     * @return array The prepared option value
     */
    protected function prepareAttributeOptionValue($optionId, $storeId, $value)
    {

        // return the prepared option value
        return $this->initializeEntity(
            array(
                MemberNames::OPTION_ID  => $optionId,
                MemberNames::STORE_ID   => $storeId,
                MemberNames::VALUE      => $value
            )
        );
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @param integer $optionId The option ID of the swatch to create
     * @param integer $storeId  The store ID to create the swatch for
     * @param mixed   $value    The swatch value itself
     * @param string  $type     The type of the swatch
     *
     * @return array The prepared option swatch
     */
    protected function prepareAttributeOptionSwatch($optionId, $storeId, $value, $type)
    {

        // return the prepared option swatch
        return $this->initializeEntity(
            array(
                MemberNames::OPTION_ID  => $optionId,
                MemberNames::STORE_ID   => $storeId,
                MemberNames::VALUE      => $value,
                MemberNames::TYPE       => $type
            )
        );
    }

    /**
     * Initialize's and return's a new entity with the status 'create'.
     *
     * @param array $attr The attributes to merge into the new entity
     *
     * @return array The initialized entity
     */
    protected function initializeEntity(array $attr = array())
    {
        return array_merge(array(EntityStatus::MEMBER_NAME => EntityStatus::STATUS_CREATE), $attr);
    }

    /**
     * Return's the unique identifier of the actual row, e. g. a products SKU.
     *
     * @return mixed The row's unique identifier
     */
    protected function getUniqueIdentifier()
    {
        return $this->getValue('sku');
    }

    /**
     * Return's the store ID of the actual row, or of the default store
     * if no store view code is set in the CSV file.
     *
     * @param string|null $default The default store view code to use, if no store view code is set in the CSV file
     *
     * @return integer The ID of the actual store
     * @throws \Exception Is thrown, if the store with the actual code is not available
     */
    protected function getRowStoreId($default = null)
    {
        return $this->getSubject()->getRowStoreId($default);
    }

    /**
     * Return's the EAV attribute with the passed entity type ID and code.
     *
     * @param integer $entityTypeId  The entity type ID of the EAV attribute to return
     * @param string  $attributeCode The code of the EAV attribute to return
     *
     * @return array The EAV attribute
     */
    protected function loadAttributeByEntityTypeIdAndAttributeCode($entityTypeId, $attributeCode)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeByEntityTypeIdAndAttributeCode($entityTypeId, $attributeCode);
    }

    /**
     * Load's and return's the EAV attribute option with the passed entity type ID, code, store ID and value.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load the option for
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option
     */
    protected function loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value);
    }

    /**
     * Load's and return's the EAV attribute option value with the passed entity type ID, code, store ID and value.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load the option value for
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option value
     */
    protected function loadAttributeOptionValueByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeOptionValueByEntityTypeIdAndAttributeCodeAndStoreIdAndValue($entityTypeId, $attributeCode, $storeId, $value);
    }

    /**
     * Load's and return's the EAV attribute option with the passed entity type ID and code, store ID, swatch and type.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load the option for
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $swatch        The swatch of the attribute option to load
     * @param string  $type          The swatch type of the attribute option to load
     *
     * @return array The EAV attribute option
     */
    protected function loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndSwatchAndType($entityTypeId, $attributeCode, $storeId, $swatch, $type)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeOptionByEntityTypeIdAndAttributeCodeAndStoreIdAndSwatchAndType($entityTypeId, $attributeCode, $storeId, $swatch, $type);
    }

    /**
     * Load's and return's the EAV attribute option swatch with the passed entity type ID, code, store ID, value and type.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load the option swatch for
     * @param string  $attributeCode The code of the EAV attribute option swatch to load
     * @param integer $storeId       The store ID of the attribute option swatch to load
     * @param string  $value         The value of the attribute option swatch to load
     * @param string  $type          The type of the attribute option swatch to load
     *
     * @return array The EAV attribute option swatch
     */
    protected function loadAttributeOptionSwatchByEntityTypeIdAndAttributeCodeAndStoreIdAndValueAndType($entityTypeId, $attributeCode, $storeId, $value, $type)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeOptionSwatchByEntityTypeIdAndAttributeCodeAndStoreIdAndValueAndType($entityTypeId, $attributeCode, $storeId, $value, $type);
    }

    /**
     * Persist's the passed EAV attribute option data and return's the ID.
     *
     * @param array       $attributeOption The attribute option data to persist
     * @param string|null $name            The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted attribute
     */
    protected function persistAttributeOption(array $attributeOption, $name = null)
    {
        return $this->getAttributeBunchProcessor()->persistAttributeOption($attributeOption);
    }

    /**
     * Persist's the passed EAV attribute option value data and return's the ID.
     *
     * @param array       $attributeOptionValue The attribute option value data to persist
     * @param string|null $name                 The name of the prepared statement that has to be executed
     *
     * @return void
     */
    protected function persistAttributeOptionValue(array $attributeOptionValue, $name = null)
    {
        $this->getAttributeBunchProcessor()->persistAttributeOptionValue($attributeOptionValue);
    }

    /**
     * Persist the passed attribute option swatch.
     *
     * @param array       $attributeOptionSwatch The attribute option swatch to persist
     * @param string|null $name                  The name of the prepared statement that has to be executed
     *
     * @return void
     */
    protected function persistAttributeOptionSwatch(array $attributeOptionSwatch, $name = null)
    {
        $this->getAttributeBunchProcessor()->persistAttributeOptionSwatch($attributeOptionSwatch);
    }
}
