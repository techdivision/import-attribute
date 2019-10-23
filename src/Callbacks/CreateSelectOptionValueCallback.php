<?php

/**
 * TechDivision\Import\Attribute\Callbacks\CreateSelectOptionValueCallback
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

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Callbacks\AbstractCallback;
use TechDivision\Import\Observers\AttributeCodeAndValueAwareObserverInterface;

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
class CreateSelectOptionValueCallback extends AbstractCallback
{

    /**
     * The option value and swatch handler instance.
     *
     * @var \TechDivision\Import\Attribute\Callbacks\OptionValueAndSwatchHandlerInterface
     */
    protected $optionValueAndSwatchHandler;

    /**
     * Initialize the callback with the passed option value and swatch handler instance.
     *
     * @param \TechDivision\Import\Attribute\Callbacks\OptionValueAndSwatchHandlerInterface $optionValueAndSwatchHandler The option value and swatch handler instance
     */
    public function __construct(OptionValueAndSwatchHandlerInterface $optionValueAndSwatchHandler)
    {
        $this->optionValueAndSwatchHandler = $optionValueAndSwatchHandler;
    }

    /**
     * Returns the option value and swatch handler instance.
     *
     * @return \TechDivision\Import\Attribute\Callbacks\OptionValueAndSwatchHandlerInterface The option value and swatch handler instance
     */
    protected function getOptionValueAndSwatchHandler()
    {
        return $this->optionValueAndSwatchHandler;
    }

    /**
     * Return's the product SKU as unique identifier of the actual row.
     *
     * @return mixed The row's unique identifier
     */
    protected function getUniqueIdentifier()
    {
        return $this->getValue('sku');
    }

    /**
     * Will be invoked by a observer it has been registered for.
     *
     * @param \TechDivision\Import\Observers\AttributeCodeAndValueAwareObserverInterface|null $observer The observer
     *
     * @return mixed The modified value
     */
    public function handle(AttributeCodeAndValueAwareObserverInterface $observer = null)
    {

        // set the observer
        $this->setObserver($observer);

        // load the attribute code and value
        $attributeCode = $observer->getAttributeCode();
        $attributeValue = $observer->getAttributeValue();

        // load the store ID
        $storeId = $this->getStoreId(StoreViewCodes::ADMIN);

        // create the option + swatch/value if necessary
        $this->getOptionValueAndSwatchHandler()->createOptionValueOrSwatchIfNecessary($attributeCode, $storeId, $attributeValue);

        // return the attribute value
        return $attributeValue;
    }
}
