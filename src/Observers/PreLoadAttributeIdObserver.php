<?php

/**
 * TechDivision\Import\Attribute\Observers\PreLoadAttributeIdObserver
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
 * Observer that pre-loads the attribute ID of the EAV attribute with the code found in the CSV file.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class PreLoadAttributeIdObserver extends AbstractAttributeImportObserver
{

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // query whether or not, we've found a new attribute code => means we've found a new EAV attribute
        if ($this->hasBeenProcessed($attributeCode = $this->getValue(ColumnKeys::ATTRIBUTE_CODE))) {
            return;
        }

        // preserve the attribute ID for the EAV attribute with the passed code
        $this->preLoadAttributeId($attributeCode);
    }

    /**
     * Pre-load the attribute ID for the EAV attribute with the passed code.
     *
     * @param string $attributeCode The code of the EAV attribute to pre-load
     *
     * @return void
     */
    protected function preLoadAttributeId($attributeCode)
    {
        return $this->getSubject()->preLoadAttributeId($attributeCode);
    }
}
