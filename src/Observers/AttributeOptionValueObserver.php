<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionValueObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Observers;

use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * Observer that create's the attribute option values found in the additional CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionValueObserver extends AbstractAttributeImportObserver
{

    /**
     * The attribute processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

    /**
     * Initializes the observer with the passed subject instance.
     *
     * @param \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface $attributeBunchProcessor The attribute bunch processor instance
     */
    public function __construct(AttributeBunchProcessorInterface $attributeBunchProcessor)
    {
        $this->attributeBunchProcessor = $attributeBunchProcessor;
    }

    /**
     * Process the observer's business logic.
     *
     * @return void
     */
    protected function process()
    {

        // prepare the store view code
        $this->prepareStoreViewCode();

        // prepare and insert the attribute option value
        try {
            $this->persistAttributeOptionValue($this->initializeAttribute($this->prepareAttributes()));
        } catch (\Exception $e) {
            // prepare a log message
            $message = sprintf(
                'For attribute code "%s" the are an error on value update! Message: %s',
                $this->getValue(ColumnKeys::ATTRIBUTE_CODE),
                $e->getMessage()
            );
            $this->getSubject()
                ->getSystemLogger()
                ->error($this->getSubject()->appendExceptionSuffix($message));
            // log a error if debug mode has been enabled and the file is NOT available
            if (!$this->getSubject()->isStrictMode()) {
                $this->mergeStatus(
                    array(
                        RegistryKeys::NO_STRICT_VALIDATIONS => array(
                            basename($this->getFilename()) => array(
                                $this->getLineNumber() => array(
                                    MemberNames::VALUE =>  $message
                                )
                            )
                        )
                    )
                );
                return;
            }
            throw $e;
        }
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the option ID
        $optionId = $this->getLastOptionId();

        // load the store ID
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);

        // load the attribute option value
        $value = $this->getValue(ColumnKeys::VALUE);

        // return the prepared attribute option
        return $this->initializeEntity(
            array(
                MemberNames::OPTION_ID  => $optionId,
                MemberNames::STORE_ID   => $storeId,
                MemberNames::VALUE      => $value
            )
        );
    }

    /**
     * Initialize the EAV attribute option value with the passed attributes and returns an instance.
     *
     * @param array $attr The EAV attribute option value attributes
     *
     * @return array The initialized EAV attribute option value
     */
    protected function initializeAttribute(array $attr)
    {
        return $attr;
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
     * Return's the ID of the option that has been created recently.
     *
     * @return integer The option ID
     */
    protected function getLastOptionId()
    {
        return $this->getSubject()->getLastOptionId();
    }

    /**
     * Persist the passed attribute option value.
     *
     * @param array $attributeOptionValue The attribute option value to persist
     *
     * @return void
     */
    protected function persistAttributeOptionValue(array $attributeOptionValue)
    {
        return $this->getAttributeBunchProcessor()->persistAttributeOptionValue($attributeOptionValue);
    }
}
