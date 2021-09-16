<?php

/**
 * TechDivision\Import\Attribute\Subjects\OptionSubject
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
use TechDivision\Import\Subjects\CastValueSubjectInterface;
use TechDivision\Import\Subjects\FileUploadSubjectInterface;
use TechDivision\Import\Subjects\FileUploadTrait;
use TechDivision\Import\Utils\BackendTypeKeys;
use TechDivision\Import\Utils\FileUploadConfigurationKeys;

/**
 * The subject implementation that handles the business logic to persist attribute options.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class OptionSubject extends AbstractAttributeSubject implements OptionSubjectInterface, FileUploadSubjectInterface, CastValueSubjectInterface
{

    /**
     * The trait that provides file upload functionality.
     *
     * @var \TechDivision\Import\Subjects\FileUploadTrait
     */
    use FileUploadTrait;

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
     * Initializes the previously loaded global data for exactly one bunch.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     */
    public function setUp($serial)
    {

        // initialize the flag whether to copy images or not
        if ($this->getConfiguration()->hasParam(FileUploadConfigurationKeys::COPY_IMAGES)) {
            $this->setCopyImages($this->getConfiguration()->getParam(FileUploadConfigurationKeys::COPY_IMAGES));
        }

        // initialize the flag whether to override images or not
        if ($this->getConfiguration()->hasParam(FileUploadConfigurationKeys::OVERRIDE_IMAGES)) {
            $this->setOverrideImages($this->getConfiguration()->getParam(FileUploadConfigurationKeys::OVERRIDE_IMAGES));
        }

        // initialize media directory => can be absolute or relative
        if ($this->getConfiguration()->hasParam(FileUploadConfigurationKeys::MEDIA_DIRECTORY)) {
            try {
                $this->setMediaDir($this->resolvePath($this->getConfiguration()
                    ->getParam(FileUploadConfigurationKeys::MEDIA_DIRECTORY)));
            } catch (\InvalidArgumentException $iae) {
                // only if we wanna copy images we need directories
                if ($this->hasCopyImages()) {
                    $this->getSystemLogger()->debug($iae->getMessage());
                }
            }
        }

        // initialize images directory => can be absolute or relative
        if ($this->getConfiguration()->hasParam(FileUploadConfigurationKeys::IMAGES_FILE_DIRECTORY)) {
            try {
                $this->setImagesFileDir($this->resolvePath($this->getConfiguration()
                    ->getParam(FileUploadConfigurationKeys::IMAGES_FILE_DIRECTORY)));
            } catch (\InvalidArgumentException $iae) {
                // only if we wanna copy images we need directories
                if ($this->hasCopyImages()) {
                    $this->getSystemLogger()->debug($iae->getMessage());
                }
            }
        }

        // prepare the callbacks
        parent::setUp($serial);
    }

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

    /**
     * Cast's the passed value based on the backend type information.
     *
     * @param string $backendType The backend type to cast to
     * @param mixed  $value       The value to be casted
     *
     * @return mixed The casted value
     */
    public function castValueByBackendType($backendType, $value)
    {

        // cast the value to a valid timestamp
        if ($backendType === BackendTypeKeys::BACKEND_TYPE_DATETIME) {
            return $this->getDateConverter()->convert($value);
        }

        // cast the value to a string that represents the float/decimal value, because
        // PHP will cast float values implicitly to the system locales format when
        // rendering as string, e. g. with echo
        if ($backendType === BackendTypeKeys::BACKEND_TYPE_FLOAT ||
            $backendType === BackendTypeKeys::BACKEND_TYPE_DECIMAL
        ) {
            return (string) $this->getNumberConverter()->parse($value);
        }

        // cast the value to an integer
        if ($backendType === BackendTypeKeys::BACKEND_TYPE_INT) {
            return (integer) $value;
        }

        // we don't need to cast strings
        return $value;
    }
}
