<?php

/**
 * TechDivision\Import\Attribute\Observers\AbstractAttributeImportObserver
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

use TechDivision\Import\Attribute\Utils\ColumnKeys;
use TechDivision\Import\Observers\AbstractObserver;
use TechDivision\Import\Subjects\SubjectInterface;

/**
 * Abstract attribute observer that handles the process to import attribute bunches.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
abstract class AbstractAttributeImportObserver extends AbstractObserver implements AttributeImportObserverInterface
{

    /**
     * Will be invoked by the action on the events the listener has been registered for.
     *
     * @param \TechDivision\Import\Subjects\SubjectInterface $subject The subject instance
     *
     * @return array The modified row
     * @see \TechDivision\Import\Observers\ObserverInterface::handle()
     */
    public function handle(SubjectInterface $subject)
    {

        // initialize the row
        $this->setSubject($subject);
        $this->setRow($subject->getRow());

        // process the functionality and return the row
        $this->process();

        // return the processed row
        return $this->getRow();
    }

    /**
     * Return's whether or not this is the admin store view.
     *
     * @return boolean TRUE if we're in admin store view, else FALSE
     */
    protected function isAdminStore()
    {
        return $this->getValue(ColumnKeys::STORE_VIEW_CODE) === null;
    }

    /**
     * Returns the entity type ID for the passed code, or if no entity type code has
     * been passed, the default one from the configuration will be used.
     *
     * @param string|null $entityTypeCode The entity type code
     *
     * @return integer The actual entity type ID
     */
    protected function getEntityTypeId($entityTypeCode = null)
    {
        return $this->getSubject()->getEntityTypeId($entityTypeCode);
    }

    /**
     * Process the observer's business logic.
     *
     * @return void
     */
    abstract protected function process();
}
