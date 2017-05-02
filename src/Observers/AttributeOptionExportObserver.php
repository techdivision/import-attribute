<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionExportObserver
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

namespace TechDivision\Import\Attribute\Observers;

use TechDivision\Import\Attribute\Utils\ColumnKeys;

/**
 * Observer that exports the attribute options to an additional CSV file for further processing.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionExportObserver extends AbstractAttributeImportObserver
{

    /**
     * The artefact type.
     *
     * @var string
     */
    const ARTEFACT_TYPE = 'option-import';

    /**
     * Process the observer's business logic.
     *
     * @return void
     */
    protected function process()
    {

        // initialize the array with the artefacts
        $artefacts = array();

        // load the attribute option values
        $attributeOptionValues = $this->getValue(ColumnKeys::ATTRIBUTE_OPTION_VALUES, array(), array($this, 'explode'));
        $attributeOptionPositions = $this->getValue(ColumnKeys::ATTRIBUTE_OPTION_POSITIONS, array(), array($this, 'explode'));

        // iterate over the attribute option values and export them
        foreach ($attributeOptionValues as $key => $attributeOptionValue) {
            // initialize and add the new artefact
            $artefacts[] = $this->newArtefact(
                array(
                    ColumnKeys::STORE_VIEW_CODE => $this->getValue(ColumnKeys::STORE_VIEW_CODE),
                    ColumnKeys::ATTRIBUTE_CODE  => $this->getValue(ColumnKeys::ATTRIBUTE_CODE),
                    ColumnKeys::VALUE           => $attributeOptionValue,
                    ColumnKeys::POSITION        => isset($attributeOptionPositions[$key]) ? $attributeOptionPositions[$key] : 0
                ),
                array(
                    ColumnKeys::STORE_VIEW_CODE => ColumnKeys::STORE_VIEW_CODE,
                    ColumnKeys::ATTRIBUTE_CODE  => ColumnKeys::ATTRIBUTE_CODE,
                    ColumnKeys::VALUE           => ColumnKeys::VALUE,
                    ColumnKeys::POSITION        => ColumnKeys::POSITION
                )
            );
        }

        // add the array with the artefacts
        $this->addArtefacts($artefacts);
    }

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
     * @param array $artefacts The product type artefacts
     *
     * @return void
     * @uses \TechDivision\Import\Product\Bundle\Subjects\BunchSubject::getLastEntityId()
     */
    protected function addArtefacts(array $artefacts)
    {
        $this->getSubject()->addArtefacts(AttributeOptionExportObserver::ARTEFACT_TYPE, $artefacts);
    }
}
