<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionSwatchObserver
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
use TechDivision\Import\Attribute\Utils\SwatchTypes;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;
use TechDivision\Import\Dbal\Utils\EntityStatus;
use TechDivision\Import\Observers\StateDetectorInterface;

/**
 * Observer that create's the attribute option swatchs found in the additional CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionSwatchObserver extends AbstractAttributeImportObserver
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

        // prepare the store view code
        $this->prepareStoreViewCode();

        // prepare and insert the attribute option swatch
        if ($attr = $this->prepareAttributes()) {
            // query whether or not the attribute option swatch has changed and has to be persisted
            $initialized = $this->initializeAttribute($attr);
            if ($this->shouldPersist($initialized)) {
                $this->persistAttributeOptionSwatch($initialized);
            }
        }
    }

    /**
     * Queries whether or not the swatch has to be persisted. For existing image swatches, the decision is deferred
     * entirely to AttributeOptionSwatchFileUploadObserver, since comparing the raw CSV filename reference (which is all
     * this observer has access to) against the already-uploaded DB path here would always appear "changed" and defeat
     * the diff - the file upload observer compares against the actually uploaded, stable target path instead.
     *
     * @param array $entity The (merged) entity to query
     *
     * @return boolean TRUE if the entity has to be persisted here, else FALSE
     */
    protected function shouldPersist(array $entity): bool
    {
        // nothing to do, if nothing has changed at all
        if (!$this->hasChanges($entity)) {
            return false;
        }

        // for existing (= status update) image swatches, defer to the file upload observer
        if ($entity[EntityStatus::MEMBER_NAME] === EntityStatus::STATUS_UPDATE && isset($entity[MemberNames::TYPE])
            && (int)$entity[MemberNames::TYPE] === SwatchTypes::IMAGE) {
            return false;
        }

        return true;
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

        // load the store ID, value + type
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);
        $value = $this->getValue(ColumnKeys::SWATCH_VALUE);
        $type = $this->getValue(ColumnKeys::SWATCH_TYPE);

        // load the attribute option swatch value/type
        if ($value !== null && $type !== null) {
            // return the prepared attribute option
            return $this->initializeEntity(
                array(
                    MemberNames::OPTION_ID  => $optionId,
                    MemberNames::STORE_ID   => $storeId,
                    MemberNames::VALUE      => $value,
                    MemberNames::TYPE       => $type
                )
            );
        }
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
     * Persist the passed attribute option swatch.
     *
     * @param array $attributeOptionSwatch The attribute option swatch to persist
     *
     * @return void
     */
    protected function persistAttributeOptionSwatch(array $attributeOptionSwatch)
    {
        return $this->getAttributeBunchProcessor()->persistAttributeOptionSwatch($attributeOptionSwatch);
    }
}
