<?php

/**
 * TechDivision\Import\Attribute\Callbacks\SwatchTypeLoaderInterface
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Callbacks;

/**
 * Interface for a swatch type loader implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
interface SwatchTypeLoaderInterface
{

    /**
     * Returns the swatch type for the attribute with the passed code and entity type ID.
     *
     * @param integer $entityTypeId  The entity type ID of the EAV attribute to return the swatch type for
     * @param string  $attributeCode The attribute code
     *
     * @return string|null The swatch type (either one of 'text' or 'visual') or NULL, if the attribute is NOT a swatch type
     */
    public function loadSwatchType($entityTypeId, $attributeCode);
}
