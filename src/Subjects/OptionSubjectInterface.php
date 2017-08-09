<?php

/**
 * TechDivision\Import\Attribute\Subjects\OptionSubjectInterface
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

namespace TechDivision\Import\Attribute\Subjects;

/**
 * The abstract product subject implementation that provides basic attribute
 * handling business logic.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
interface OptionSubjectInterface extends AttributeSubjectInterface
{

    /**
     * Queries whether or not the attribute with the passed code/value has already been processed.
     *
     * @param string $attributeCode The attribute code to check
     * @param string $value         The option value to check
     *
     * @return boolean TRUE if the path has been processed, else FALSE
     */
    public function hasBeenProcessed($attributeCode, $value);

    /**
     * Return's the ID of the option that has been created recently.
     *
     * @return integer The option ID
     */
    public function getLastOptionId();
}
