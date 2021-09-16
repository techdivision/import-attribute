<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionValueExportObserver
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

/**
 * Observer that exports the attribute options to an additional CSV file for further processing.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionValueExportObserver extends AbstractAttributeExportObserver
{

    /**
     * The artefact type.
     *
     * @var string
     */
    const ARTEFACT_TYPE = 'option-value-import';

    /**
     * Process the observer's business logic.
     *
     * @return void
     */
    protected function process()
    {

        // do NOT export the value if we're NOT in the admin store view
        if ($this->isAdminStore()) {
            return;
        }

        // prepare the store view code
        $this->prepareStoreViewCode();

        // initialize the array with the artefacts
        $artefacts = array();

        // load the attribute option values for the custom store views
        $attributeOptionValues = $this->getValue(ColumnKeys::ATTRIBUTE_OPTION_VALUES, array(), function ($value) {
            return $this->explode($value, $this->getMultipleFieldDelimiter());
        });

        // iterate over the attribute option values and export them
        foreach ($attributeOptionValues as $key => $attributeOptionValue) {
            // load the artefacts with the admin store values
            $adminValueArtefacts = $this->getArtefactsByTypeAndEntityId(AttributeOptionExportObserver::ARTEFACT_TYPE, $this->getLastEntityId());

            // initialize and add the new artefact
            $artefacts[] = $this->newArtefact(
                array(
                    ColumnKeys::STORE_VIEW_CODE   => $this->getValue(ColumnKeys::STORE_VIEW_CODE),
                    ColumnKeys::ATTRIBUTE_CODE    => $this->getValue(ColumnKeys::ATTRIBUTE_CODE),
                    ColumnKeys::ADMIN_STORE_VALUE => $adminValueArtefacts[$key][ColumnKeys::VALUE],
                    ColumnKeys::VALUE             => $attributeOptionValue
                ),
                array(
                    ColumnKeys::STORE_VIEW_CODE   => ColumnKeys::STORE_VIEW_CODE,
                    ColumnKeys::ATTRIBUTE_CODE    => ColumnKeys::ATTRIBUTE_CODE,
                    ColumnKeys::ADMIN_STORE_VALUE => ColumnKeys::ADMIN_STORE_VALUE,
                    ColumnKeys::VALUE             => ColumnKeys::VALUE
                )
            );
        }

        // add the array with the artefacts
        $this->addArtefacts($artefacts, false);
    }

    /**
     * Return's the artefact type used for export.
     *
     * @return string The artefact type
     */
    protected function getArtefactType()
    {
        return AttributeOptionValueExportObserver::ARTEFACT_TYPE;
    }
}
