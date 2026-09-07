<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeLabelObserver
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

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;
use TechDivision\Import\Observers\StateDetectorInterface;

/**
 * Observer that create's the EAV attribute label.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeLabelObserver extends AbstractAttributeImportObserver
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
     * @param \TechDivision\Import\Observers\StateDetectorInterface|null               $stateDetector           The state detector instance to use
     */
    public function __construct(
        AttributeBunchProcessorInterface $attributeBunchProcessor,
        ?StateDetectorInterface $stateDetector = null
    ) {
        $this->attributeBunchProcessor = $attributeBunchProcessor;

        // pass the state detector to the parent method
        parent::__construct($stateDetector);
    }

    /**
     * Process the observer's business logic.
     *
     * @return void
     */
    protected function process()
    {

        // do nothing, if we're in admin store view
        if ($this->isAdminStore()) {
            return;
        }

        // prepare the store view code
        $this->prepareStoreViewCode();

        // query whether or not an value for the attribute label is available
        if ($attributeLabel = $this->prepareAttributes()) {
            // initialize the attribute label
            $attributeLabel = $this->initializeAttribute($attributeLabel);

            // query whether or not the attribute label has changed and has to be persisted
            if ($this->hasChanges($attributeLabel)) {
                // prepare and persist the attribue label
                $this->persistAttributeLabel($attributeLabel);
            }
        }
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the frontend label value
        if ($frontendLabel = $this->getValue(ColumnKeys::FRONTEND_LABEL)) {
            // load the last attribute ID
            $attributeId = $this->getLastAttributeId();

            // load the store ID
            $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);

            // return the prepared attribute label
            return $this->initializeEntity(
                array(
                    MemberNames::ATTRIBUTE_ID  => $attributeId,
                    MemberNames::STORE_ID      => $storeId,
                    MemberNames::VALUE         => $frontendLabel
                )
            );
        }
    }

    /**
     * Initialize the attribute with the passed attributes and returns an instance.
     *
     * @param array $attr The attribute attributes
     *
     * @return array The initialized attribute
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
     * Return's the ID of the attribute that has been created recently.
     *
     * @return integer The attribute ID
     */
    protected function getLastAttributeId()
    {
        return $this->getSubject()->getLastAttributeId();
    }

    /**
     * Persist the passed attribute label.
     *
     * @param array $attributeLabel The attribute label to persist
     *
     * @return void
     */
    protected function persistAttributeLabel(array $attributeLabel)
    {
        return $this->getAttributeBunchProcessor()->persistAttributeLabel($attributeLabel);
    }
}
