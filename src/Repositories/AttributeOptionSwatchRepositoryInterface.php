<?php

/**
 * TechDivision\Import\Attribute\Repositories\AttributeOptionSwatchRepositoryInterface
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Repositories;

use TechDivision\Import\Dbal\Repositories\RepositoryInterface;

/**
 * Interface for repository implementations to load EAV attribute option swatch data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
interface AttributeOptionSwatchRepositoryInterface extends RepositoryInterface
{

    /**
     * Load's and return's the EAV attribute option swatch with the passed option ID and store ID
     *
     * @param string  $optionId The option ID of the attribute option swatch to load
     * @param integer $storeId  The store ID of the attribute option swatch to load
     *
     * @return array The EAV attribute option swatch
     */
    public function findOneByOptionIdAndStoreId($optionId, $storeId);

    /**
     * Load's and return's the EAV attribute option swatch with the passed entity type ID, code, store ID, value and type.
     *
     * @param string  $entityTypeId  The entity type ID of the EAV attribute to load the option swatch for
     * @param string  $attributeCode The code of the EAV attribute option swatch to load
     * @param integer $storeId       The store ID of the attribute option swatch to load
     * @param string  $value         The value of the attribute option swatch to load
     * @param string  $type          The type of the attribute option swatch to load
     *
     * @return array The EAV attribute option swatch
     */
    public function findOneByEntityTypeIdAndAttributeCodeAndStoreIdAndValueAndType($entityTypeId, $attributeCode, $storeId, $value, $type);
}
