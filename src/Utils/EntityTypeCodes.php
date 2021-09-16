<?php

/**
 * TechDivision\Import\Attribute\Utils\EntityTypeCodes
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Utils;

/**
 * Utility class containing the entity type codes.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class EntityTypeCodes extends \TechDivision\Import\Utils\EntityTypeCodes
{

    /**
     * Key for the product entity 'eav_attribute_option'.
     *
     * @var string
     */
    const EAV_ATTRIBUTE_OPTION = 'eav_attribute_option';

    /**
     * Key for the product entity 'eav_entity_attribute'.
     *
     * @var string
     */
    const EAV_ENTITY_ATTRIBUTTE = 'eav_entity_attribute';

    /**
     * Key for the product entity 'catalog_eav_attribute'.
     *
     * @var string
     */
    const CATALOG_EAV_ATTRIBUTE = 'catalog_eav_attribute';
}
