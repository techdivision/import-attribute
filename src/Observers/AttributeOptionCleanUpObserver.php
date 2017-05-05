<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionCleanUpObserver
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
 * Clean-Up after importing the EAV attribute option row.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionCleanUpObserver extends AbstractAttributeImportObserver
{

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {
        $this->addAddtributeCodeValueOptionIdMapping($this->getValue(ColumnKeys::ATTRIBUTE_CODE), $this->getValue(ColumnKeys::VALUE));
    }

    /**
     * Map's the passed attribue code and value to the option ID that has been created recently.
     *
     * @param string $attributeCode The attriburte code that has to be mapped
     * @param string $value         The value that has to be mapped
     *
     * @return void
     */
    protected function addAddtributeCodeValueOptionIdMapping($attributeCode, $value)
    {
        $this->getSubject()->addAddtributeCodeValueOptionIdMapping($attributeCode, $value);
    }
}
