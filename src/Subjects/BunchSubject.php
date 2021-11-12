<?php

/**
 * TechDivision\Import\Attribute\Subjects\BunchSubject
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Subjects;

use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\RegistryKeys;
use TechDivision\Import\Subjects\ExportableTrait;
use TechDivision\Import\Subjects\ExportableSubjectInterface;

/**
 * The subject implementation that handles the business logic to persist attributes.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class BunchSubject extends AbstractAttributeSubject implements BunchSubjectInterface, ExportableSubjectInterface
{

    /**
     * The trait that implements the export functionality.
     *
     * @var \TechDivision\Import\Subjects\ExportableTrait;
     */
    use ExportableTrait;

    /**
     * The ID of the attribute that has been created recently.
     *
     * @var integer
     */
    protected $lastAttributeId;

    /**
     * The array with the available attribute sets.
     *
     * @var array
     */
    protected $attributeSets = array();

    /**
     * The array with the available attribute groups.
     *
     * @var array
     */
    protected $attributeGroups = array();

    /**
     * The array with the pre-loaded attribute IDs.
     *
     * @var array
     */
    protected $preLoadedAttributeIds = array();

    /**
     * The attribute code => attribute ID mapping.
     *
     * @var array
     */
    protected $attributeCodeIdMapping = array();

    /**
     * Intializes the previously loaded global data for exactly one bunch.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     */
    public function setUp($serial)
    {

        // load the status of the actual import
        $status = $this->getRegistryProcessor()->getAttribute(RegistryKeys::STATUS);

        // load the global data we've prepared initially
        $this->attributeSets = $status[RegistryKeys::GLOBAL_DATA][RegistryKeys::ATTRIBUTE_SETS];
        $this->attributeGroups = $status[RegistryKeys::GLOBAL_DATA][RegistryKeys::ATTRIBUTE_GROUPS];

        // prepare the callbacks
        parent::setUp($serial);
    }

    /**
     * Clean up the global data after importing the bunch.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     */
    public function tearDown($serial)
    {

        // invoke the parent method
        parent::tearDown($serial);

        // load the registry processor
        $registryProcessor = $this->getRegistryProcessor();

        // update the status
        $registryProcessor->mergeAttributesRecursive(
            RegistryKeys::STATUS,
            array(
                RegistryKeys::PRE_LOADED_ATTRIBUTE_IDS => $this->preLoadedAttributeIds,
            )
        );
    }

    /**
     * Return's the attribute set with the passed attribute set name.
     *
     * @param string $attributeSetName The name of the requested attribute set
     * @param string $entityTypeCode   The entity type code of the requested attribute set
     *
     * @return array The EAV attribute set
     * @throws \Exception Is thrown, if the attribute set with the passed name is not available
     */
    public function getAttributeSetByAttributeSetNameAndEntityTypeCode($attributeSetName, $entityTypeCode)
    {

        // query whether or not attribute sets for the actual entity type code are available
        if (isset($this->attributeSets[$entityTypeCode])) {
            // load the attribute sets for the actualy entity type code
            $attributSets = $this->attributeSets[$entityTypeCode];

            // query whether or not, the requested attribute set is available
            if (isset($attributSets[$attributeSetName])) {
                return $attributSets[$attributeSetName];
            }
        }

        // throw an exception, if not
        throw new \Exception(
            sprintf(
                'Found invalid attribute set name %s in file %s on line %d',
                $attributeSetName,
                $this->getFilename(),
                $this->getLineNumber()
            )
        );
    }

    /**
     * Return's the attribute group with the passed attribute set/group name.
     *
     * @param string $entityTypeCode     The entity type code of the requested attribute group
     * @param string $attributeSetName   The name of the requested attribute group's attribute set
     * @param string $attributeGroupName The name of the requested attribute group
     *
     * @return array The EAV attribute group
     * @throws \Exception Is thrown, if the attribute group with the passed attribute set/group name is not available
     */
    public function getAttributeGroupByEntityTypeCodeAndAttributeSetNameAndAttributeGroupName($entityTypeCode, $attributeSetName, $attributeGroupName)
    {

        // query whether or not attribute groups for the actual entity type code are available
        if (isset($this->attributeGroups[$entityTypeCode])) {
            // query whether or not, the attribute group with the passed set/group name is available
            if (isset($this->attributeGroups[$entityTypeCode][$attributeSetName][$attributeGroupName])) {
                return $this->attributeGroups[$entityTypeCode][$attributeSetName][$attributeGroupName];
            }
        }

        // throw an exception, if not
        throw new \Exception(
            sprintf(
                'Found invalid attribute group with entity type code %s attribute set/group name %s/%s in file %s on line %d',
                $entityTypeCode,
                $attributeSetName,
                $attributeGroupName,
                $this->getFilename(),
                $this->getLineNumber()
            )
        );
    }

    /**
     * Pre-load and temporary persist the attribute ID for the passed EAV attribute.
     *
     * @param array $attribute The EAV attribute to pre-load
     *
     * @return void
     */
    public function preLoadAttributeId(array $attribute)
    {
        $this->preLoadedAttributeIds[$attribute[MemberNames::ATTRIBUTE_CODE]]= $attribute[MemberNames::ATTRIBUTE_ID];
    }

    /**
     * Return's the ID of the attribute that has been created recently.
     *
     * @return integer The attribute ID
     */
    public function getLastEntityId()
    {
        return $this->getLastAttributeId();
    }

    /**
     * Map's the passed attribute code to the attribute ID that has been created recently.
     *
     * @param string $attributeCode The attribute code that has to be mapped
     *
     * @return void
     */
    public function addAttributeCodeIdMapping($attributeCode)
    {
        $this->attributeCodeIdMapping[$attributeCode] = $this->getLastEntityId();
    }

    /**
     * Queries whether or not the attribute with the passed code has already been processed.
     *
     * @param string $attributeCode The attribute code to check
     *
     * @return boolean TRUE if the path has been processed, else FALSE
     */
    public function hasBeenProcessed($attributeCode)
    {
        return isset($this->attributeCodeIdMapping[$attributeCode]);
    }

    /**
     * Set's the ID of the attribute that has been created recently.
     *
     * @param integer $lastAttributeId The attribute ID
     *
     * @return void
     */
    public function setLastAttributeId($lastAttributeId)
    {
        $this->lastAttributeId = $lastAttributeId;
    }

    /**
     * Return's the ID of the attribute that has been created recently.
     *
     * @return integer The attribute ID
     */
    public function getLastAttributeId()
    {
        return $this->lastAttributeId;
    }
}
