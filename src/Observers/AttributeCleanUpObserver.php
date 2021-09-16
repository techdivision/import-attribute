<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeCleanUpObserver
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
 * Clean-Up after importing the EAV attribute row.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeCleanUpObserver extends AbstractAttributeImportObserver
{

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {
        $this->addAttributeCodeIdMapping($this->getValue(ColumnKeys::ATTRIBUTE_CODE));
    }

    /**
     * Map's the passed attribute code to the attribute ID that has been created recently.
     *
     * @param string $attributeCode The attribute code that has to be mapped
     *
     * @return void
     */
    protected function addAttributeCodeIdMapping($attributeCode)
    {
        $this->getSubject()->addAttributeCodeIdMapping($attributeCode);
    }
}
