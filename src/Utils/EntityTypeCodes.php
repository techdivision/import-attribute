<?php

/**
 * TechDivision\Import\Attribute\Utils\EntityTypeCodes
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
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Utils;

/**
 * Utility class containing the entity type codes.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
