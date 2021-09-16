<?php

/**
 * TechDivision\Import\Attribute\Utils\RegistryKeys
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Utils;

/**
 * Utility class containing the unique registry keys.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class RegistryKeys extends \TechDivision\Import\Utils\RegistryKeys
{

    /**
     * Key for the registry entry containing the pre-loaded attribute code => ID mapping.
     *
     * @var string
     */
    const PRE_LOADED_ATTRIBUTE_IDS = 'preLoadedAttributeIds';
}
