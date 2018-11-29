<?php

/**
 * TechDivision\Import\Attribute\Callbacks\CreateMultiselectOptionValueCallback
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

use TechDivision\Import\Utils\MemberNames;
use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Services\EavAwareProcessorInterface;
use TechDivision\Import\Observers\AttributeCodeAndValueAwareObserverInterface;
use TechDivision\Import\Attribute\Services\AttributeProcessorInterface;

/**
 * A callback implementation that creates the option values, if not exists, for the passed
 * multiselect with the passed attribute code.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
abstract class CreateMultiselectOptionValueCallback extends AbstractCallback
{

    /**
     * The attribute bunch processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

    /**
     * Initialize the callback with the passed attribute bunch processor instance.
     *
     * @param \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface $attributeProcessor The attribute bunch processor instance
     */
    public function __construct(AttributeBunchProcessorInterface $attributeProcessor)
    {
        $this->attributeBunchProcessor = $attributeProcessor;
    }

    /**
     * Will be invoked by a observer it has been registered for.
     *
     * @param \TechDivision\Import\Observers\ObserverInterface $observer The observer
     *
     * @return mixed The modified value
     */
    public function handle(AttributeCodeAndValueAwareObserverInterface $observer)
    {

        // set the observer
        $this->setObserver($observer);

        // load the attribute code and value
        $attributeCode = $observer->getAttributeCode();
        $attributeValue = $observer->getAttributeValue();

        // explode the multiselect values
        $vals = explode('|', $attributeValue);

        // initialize the array for the mapped values
        $mappedValues = array();

        // convert the option values into option value ID's
        foreach ($vals as $val) {
            // load the ID of the actual store
            $storeId = $this->getStoreId(StoreViewCodes::ADMIN);

            // try to load the attribute option value and add the option ID
            if ($eavAttributeOptionValue = $this->loadAttributeOptionValueByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $val)) {
                $mappedValues[] = $eavAttributeOptionValue[MemberNames::OPTION_ID];
                continue;
            }

            // create the option value here
            error_log(sprintf("Can't find option value with attribute_code %s, store ID %d and value %s", $attributeCode, $storeId, $val));
        }

        // return NULL, if NO value can be mapped to an option
        if (sizeof($mappedValues) === 0) {
            return;
        }

        // re-concatenate and return the values
        return implode(',', $mappedValues);
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
     * Load's and return's the EAV attribute option value with the passed code, store ID and value.
     *
     * @param string  $attributeCode The code of the EAV attribute option to load
     * @param integer $storeId       The store ID of the attribute option to load
     * @param string  $value         The value of the attribute option to load
     *
     * @return array The EAV attribute option value
     */
    protected function loadAttributeOptionValueByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeOptionValueByAttributeCodeAndStoreIdAndValue($attributeCode, $storeId, $value);
    }
}
