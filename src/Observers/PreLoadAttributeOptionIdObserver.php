<?php

/**
 * TechDivision\Import\Attribute\Observers\PreLoadAttributeOptionIdObserver
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
 * Observer that pre-loads the option ID of the EAV attribute option with the attribute code/value found in the CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class PreLoadAttributeOptionIdObserver extends AbstractAttributeImportObserver
{

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // query whether or not, we've found a new attribute code => means we've found a new EAV attribute
        if ($this->hasBeenProcessed($attributeCode = $this->getValue(ColumnKeys::ATTRIBUTE_CODE), $value = $this->getValue(ColumnKeys::ADMIN_STORE_VALUE))) {
            return;
        }

        // preserve the attribute ID for the EAV attribute with the passed code
        $this->preLoadOptionId($attributeCode, $value);
    }

    /**
     * Queries whether or not the option with the passed code/value has already been processed.
     *
     * @param string $attributeCode The attribute code to check
     * @param string $value         The option value to check
     *
     * @return boolean TRUE if the path has been processed, else FALSE
     */
    protected function hasBeenProcessed($attributeCode, $value)
    {
        return $this->getSubject()->hasBeenProcessed($attributeCode, $value);
    }

    /**
     * Pre-load the option ID for the EAV attribute option with the passed attribute code/value.
     *
     * @param string $attributeCode The code of the EAV attribute to pre-load
     * @param string $value         The option admin store view value of the EAV attribute option to pre-load
     *
     * @return void
     */
    protected function preLoadOptionId($attributeCode, $value)
    {
        return $this->getSubject()->preLoadOptionId($attributeCode, $value);
    }
}
