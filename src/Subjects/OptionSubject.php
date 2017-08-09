<?php

/**
 * TechDivision\Import\Attribute\Subjects\OptionSubject
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

use TechDivision\Import\Attribute\Utils\MemberNames;

/**
 * The subject implementation that handles the business logic to persist attribute options.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class OptionSubject extends AbstractAttributeSubject implements OptionSubjectInterface
{

    /**
     * The ID of the option that has been created recently.
     *
     * @var integer
     */
    protected $lastOptionId;

    /**
     * The value => option ID mapping.
     *
     * @var array
     */
    protected $attributeCodeValueOptionIdMapping = array();

    /**
     * Map's the passed attribue code and value to the option ID that has been created recently.
     *
     * @param string $attributeCode The attriburte code that has to be mapped
     * @param string $value         The value that has to be mapped
     *
     * @return void
     */
    public function addAddtributeCodeValueOptionIdMapping($attributeCode, $value)
    {
        $this->attributeCodeValueOptionIdMapping[$attributeCode][$value] = $this->getLastEntityId();
    }

    /**
     * Queries whether or not the attribute with the passed code/value has already been processed.
     *
     * @param string $attributeCode The attribute code to check
     * @param string $value         The option value to check
     *
     * @return boolean TRUE if the path has been processed, else FALSE
     */
    public function hasBeenProcessed($attributeCode, $value)
    {
        return isset($this->attributeCodeValueOptionIdMapping[$attributeCode][$value]);
    }

    /**
     * Return's the ID of the attribute that has been created recently.
     *
     * @return integer The attribute ID
     */
    public function getLastEntityId()
    {
        return $this->getLastOptionId();
    }

    /**
     * Set's the ID of the option that has been created recently.
     *
     * @param integer $lastOptionId The option ID
     *
     * @return void
     */
    public function setLastOptionId($lastOptionId)
    {
        $this->lastOptionId = $lastOptionId;
    }

    /**
     * Return's the ID of the option that has been created recently.
     *
     * @return integer The option ID
     */
    public function getLastOptionId()
    {
        return $this->lastOptionId;
    }

    /**
     * Pre-load the option ID for the passed EAV attribute option.
     *
     * @param array $attributeOption The EAV attribute option with the ID that has to be pre-loaded
     *
     * @return void
     */
    public function preLoadOptionId(array $attributeOption)
    {
        $this->setLastOptionId($attributeOption[MemberNames::OPTION_ID]);
    }
}
