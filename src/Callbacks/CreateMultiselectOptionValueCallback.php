<?php

/**
 * TechDivision\Import\Attribute\Callbacks\CreateMultiselectOptionValueCallback
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
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
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class CreateMultiselectOptionValueCallback extends AbstractCallback
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
    public function handle(?AttributeCodeAndValueAwareObserverInterface $observer = null)
    {

        // set the observer
        $this->setObserver($observer);

        // load the attribute code and value
        $attributeCode = $observer->getAttributeCode();
        $attributeValue = $observer->getAttributeValue();

        // load the ID of the actual store
        $storeId = $this->getStoreId(StoreViewCodes::ADMIN);

        // explode the multiselect values
        $vals = explode('|', $attributeValue);

        // convert the option values into option value ID's
        foreach ($vals as $val) {
            $this->getOptionValueAndSwatchHandler()->createOptionValueIfNecessary($attributeCode, $storeId, $val);
        }

        // return the values
        return $attributeValue;
    }
}
