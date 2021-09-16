<?php

/**
 * TechDivision\Import\Attribute\Utils\SwatchTypes
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
 * Utility class containing the available swatch types.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class SwatchTypes
{

    /**
     * Type for a text swatch.
     *
     * @var integer
     */
    const TEXT = 0;

    /**
     * Type for a color swatch.
     *
     * @var integer
     */
    const COLOR = 1;

    /**
     * Type for a image swatch.
     *
     * @var integer
     */
    const IMAGE = 2;

    /**
     * This is a utility class, so protect it against direct
     * instantiation.
     */
    private function __construct()
    {
    }

    /**
     * This is a utility class, so protect it against cloning.
     *
     * @return void
     */
    private function __clone()
    {
    }
}
