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
use TechDivision\Import\Utils\RegistryKeys;

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

        $adminValueArtefacts = $this->getArtefactsByTypeAndEntityId(AttributeOptionExportObserver::ARTEFACT_TYPE, $this->getLastEntityId());

        // validate the admin values with the option values
        if (!$this->isValidateAdminValuesWithStoreOptionValues($adminValueArtefacts, $attributeOptionValues)) {
            // Skip the export if the store values are not valid
            return;
        }

        // iterate over the attribute option values and export them
        foreach ($attributeOptionValues as $key => $attributeOptionValue) {
            // query whether or not the attribute option value is available
            if (!isset($adminValueArtefacts[$key]) || empty($attributeOptionValue) || empty($adminValueArtefacts[$key])) {
                continue;
            }
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

    /**
     * @param array $adminValueArtefacts   attribute option values from admin row
     * @param array $attributeOptionValues attribute option values for the custom store views
     * @return bool
     * @throws \Exception
     */
    protected function isValidateAdminValuesWithStoreOptionValues(array $adminValueArtefacts, $attributeOptionValues): bool
    {
        if (count($adminValueArtefacts) != count($attributeOptionValues)) {
            $origin = array();
            foreach ($adminValueArtefacts as $originalData) {
                $origin[] = $originalData[ColumnKeys::VALUE];
            }
            $message =
                sprintf(
                    "Store '%s' related number of options of attribute '%s' must be identical to the global definition (%d vs. %d). Global: '%s' vs. Store: '%s'",
                    $this->getStoreViewCode(),
                    $this->getValue(ColumnKeys::ATTRIBUTE_CODE),
                    count($adminValueArtefacts),
                    count($attributeOptionValues),
                    implode(',', $origin),
                    $this->getValue(ColumnKeys::ATTRIBUTE_OPTION_VALUES),
                );
            if (!$this->getSubject()->isStrictMode()) {
                $this->getSystemLogger()->warning($message);
                $this->getSubject()->mergeStatus(
                    array(
                        RegistryKeys::NO_STRICT_VALIDATIONS => array(
                            basename($this->getSubject()->getFilename()) => array(
                                $this->getSubject()->getLineNumber() => array(
                                    ColumnKeys::ATTRIBUTE_OPTION_VALUES => $message
                                )
                            )
                        )
                    )
                );
                return false;
            } else {
                throw new \InvalidArgumentException($message);
            }
        }
        return true;
    }
}
