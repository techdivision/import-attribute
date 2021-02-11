<?php

/**
 * TechDivision\Import\Attribute\Loaders\AttributeValueLoader
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
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Loaders;

use TechDivision\Import\Loaders\LoaderInterface;
use TechDivision\Import\Attribute\Utils\MemberNames;

/**
 * Generic loader for product values.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeValueLoader implements LoaderInterface
{

    /**
     * The column name to load the values for.
     *
     * @var string
     */
    protected $columnName;

    /**
     * The registry loader instance.
     *
     * @var \TechDivision\Import\Loaders\LoaderInterface
     */
    protected $registryLoader;

    /**
     * Construct that initializes the iterator with the product processor instance.
     *
     * @param \TechDivision\Import\Loaders\LoaderInterface $registryLoader The registry loader instance
     * @param string                                       $columnName     The column name to load the values for
     */
    public function __construct(LoaderInterface $registryLoader, $columnName = MemberNames::FRONTEND_INPUT)
    {

        // initialize the column name and the registry loader
        $this->columnName = $columnName;
        $this->registryLoader = $registryLoader;
    }

    /**
     * Loads and returns data.
     *
     * @return \ArrayAccess The array with the data
     */
    public function load()
    {
        // load the already processed SKUs from the registry, merge
        // them with the ones from the DB and return them
        $collectedColumns = $this->getRegistryLoader()->load();

        // query whether or not values for the configured column name are available
        if (is_array($collectedColumns[$this->columnName])) {
            return $collectedColumns[$this->columnName];
        }

        // return an empty array otherwise
        return array();
    }

    /**
     * The registry loader instance.
     *
     * @return \TechDivision\Import\Loaders\LoaderInterface The loader instance
     */
    protected function getRegistryLoader()
    {
        return $this->registryLoader;
    }
}
