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
 * Observer that exports the attribute option swatch values to an additional CSV file for further processing.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionSwatchExportObserver extends AbstractAttributeExportObserver
{

    /**
     * The artefact type.
     *
     * @var string
     */
    const ARTEFACT_TYPE = 'option-swatch-import';

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
        $attributeOptionValues = $this->getValue(ColumnKeys::ATTRIBUTE_OPTION_VALUES, array(), array($this, 'explode'));
        $attributeOptionSwatch = $this->explode($this->getValue(ColumnKeys::ATTRIBUTE_OPTION_SWATCH), $this->getMultipleValueDelimiter());

        // load the option value keys
        $optionValueKeys = array_keys($attributeOptionValues);

        // iterate over the attribute option values and export them
        foreach ($optionValueKeys as $key) {
            // load the artefacts with the admin store values
            $adminValueArtefacts = $this->getArtefactsByTypeAndEntityId(AttributeOptionExportObserver::ARTEFACT_TYPE, $this->getLastEntityId());

            // initialize the attribute option swatch data
            $optionSwatch = array();
            if (isset($attributeOptionSwatch[$key])) {
                // prepare the EAV attribute option swatch values
                foreach ($this->explode($attributeOptionSwatch[$key]) as $value) {
                    $explodedSwatch = $this->explode($value, '=');
                    if (isset($explodedSwatch[0]) && isset($explodedSwatch[1])) {
                        $optionSwatch[$explodedSwatch[0]] = $explodedSwatch[1];
                    }
                }

                // initialize and add the new artefact
                $artefacts[] = $this->newArtefact(
                    array(
                        ColumnKeys::STORE_VIEW_CODE   => $this->getValue(ColumnKeys::STORE_VIEW_CODE),
                        ColumnKeys::ATTRIBUTE_CODE    => $this->getValue(ColumnKeys::ATTRIBUTE_CODE),
                        ColumnKeys::ADMIN_STORE_VALUE => $adminValueArtefacts[$key][ColumnKeys::VALUE],
                        ColumnKeys::SWATCH_TYPE       => isset($optionSwatch[ColumnKeys::TYPE]) ? $optionSwatch[ColumnKeys::TYPE] : null,
                        ColumnKeys::SWATCH_VALUE      => isset($optionSwatch[ColumnKeys::VALUE]) ? $optionSwatch[ColumnKeys::VALUE] : null
                    ),
                    array(
                        ColumnKeys::STORE_VIEW_CODE   => ColumnKeys::STORE_VIEW_CODE,
                        ColumnKeys::ATTRIBUTE_CODE    => ColumnKeys::ATTRIBUTE_CODE,
                        ColumnKeys::ADMIN_STORE_VALUE => $adminValueArtefacts[$key][ColumnKeys::VALUE],
                        ColumnKeys::SWATCH_TYPE       => ColumnKeys::ATTRIBUTE_OPTION_SWATCH,
                        ColumnKeys::SWATCH_VALUE      => ColumnKeys::ATTRIBUTE_OPTION_SWATCH
                    )
                );
            }
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
        return AttributeOptionSwatchExportObserver::ARTEFACT_TYPE;
    }
}
