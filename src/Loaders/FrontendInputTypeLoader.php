<?php

/**
 * TechDivision\Import\Attribute\Loaders\FrontendInputTypeLoader
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
 * @copyright 2021 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Loaders;

use TechDivision\Import\Loaders\LoaderInterface;
use TechDivision\Import\Utils\FrontendInputTypes;
use TechDivision\Import\Attribute\Utils\ColumnKeys;

/**
 * Loader for frontend input types indexed by column name which they are allowed for.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2021 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class FrontendInputTypeLoader implements LoaderInterface
{

    /**
     * The array with the allowed frontend input types, indexed with
     * the column names the frontend input types are allowed for.
     *
     * @var array
     */
    private $frontendInputTypes = array(
        ColumnKeys::ATTRIBUTE_OPTION_VALUES => array(FrontendInputTypes::SELECT, FrontendInputTypes::MULTISELECT)
    );

    /**
     * Loads and returns data.
     *
     * @return \ArrayAccess The array with the raw data
     */
    public function load()
    {
        return $this->frontendInputTypes;
    }
}
