<?php

/**
 * TechDivision\Import\Attribute\Observers\AbstractAttributeExportObserver
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

/**
 * Observer that exports the attribute options to an additional CSV file for further processing.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
abstract class AbstractAttributeExportObserver extends AbstractAttributeImportObserver
{

    /**
     * Return's the artefact type used for export.
     *
     * @return string The artefact type
     */
    abstract protected function getArtefactType();

    /**
     * Create's and return's a new empty artefact entity.
     *
     * @param array $columns             The array with the column data
     * @param array $originalColumnNames The array with a mapping from the old to the new column names
     *
     * @return array The new artefact entity
     */
    protected function newArtefact(array $columns, array $originalColumnNames)
    {
        return $this->getSubject()->newArtefact($columns, $originalColumnNames);
    }

    /**
     * Add the passed product type artefacts to the product with the
     * last entity ID.
     *
     * @param array   $artefacts The product type artefacts
     * @param boolean $override  Whether or not the artefacts for the actual entity ID has to be overwritten
     *
     * @return void
     * @uses \TechDivision\Import\Product\Bundle\Subjects\BunchSubject::getLastEntityId()
     */
    protected function addArtefacts(array $artefacts, $override = true)
    {
        $this->getSubject()->addArtefacts($this->getArtefactType(), $artefacts, $override);
    }

    /**
     * Return the artefacts for the passed type and entity ID.
     *
     * @param string $type     The artefact type, e. g. configurable
     * @param string $entityId The entity ID to return the artefacts for
     *
     * @return array The array with the artefacts
     * @throws \Exception Is thrown, if no artefacts are available
     */
    protected function getArtefactsByTypeAndEntityId($type, $entityId)
    {
        return $this->getSubject()->getArtefactsByTypeAndEntityId($type, $entityId);
    }
}
