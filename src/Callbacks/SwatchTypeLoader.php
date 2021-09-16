<?php

/**
 * TechDivision\Import\Attribute\Callbacks\SwatchTypeLoader
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Callbacks;

use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Observers\CatalogAttributeObserver;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * A loader implementation that loads the swatch type for the attribute with the given code.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class SwatchTypeLoader implements SwatchTypeLoaderInterface
{

    /**
     * The attribute bunch processor instance.
     *
     * @var \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface
     */
    protected $attributeBunchProcessor;

    /**
     * The swatch type of the actual attribute handled by the callback.
     *
     * @var array
     */
    protected $swatchType = array();

    /**
     * Initialize the callback with the passed attribute bunch processor instance.
     *
     * @param \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface $attributeProcessor The attribute bunch processor instance
     */
    public function __construct(AttributeBunchProcessorInterface $attributeProcessor)
    {
        $this->attributeBunchProcessor = $attributeProcessor;
    }

    /**
     * Returns the attribute bunch processor instance.
     *
     * @return \TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface The attribute bunch processor instance
     */
    protected function getAttributeBunchProcessor()
    {
        return $this->attributeBunchProcessor;
    }

    /**
     * Query whether or not a swatch type for the attribute with the given key has already been loaded.
     *
     * @param string $key The key to query for
     *
     * @return boolean TRUE if the swatch type has been loaded, else FALSE
     */
    protected function hasSwatchType($key)
    {
        return isset($this->swatchType[$key]);
    }

    /**
     * Set the swatch type for the given attribute key.
     *
     * @param string $key        The attribute key to set the swatch type for
     * @param string $swatchType The swatch type of the attribute with the given code
     *
     * @return void
     */
    protected function setSwatchType($key, $swatchType)
    {
        $this->swatchType[$key] = $swatchType;
    }

    /**
     * Returns the swatch type for the passed attribute key.
     *
     * @param string $key The attribute key to return the swatch type for
     *
     * @return string|null The swatch type of the attribute with the passed key or NULL
     */
    protected function getSwatchType($key)
    {

        // reutrn the swatch type, if available
        if (isset($this->swatchType[$key])) {
            return $this->swatchType[$key];
        }
    }

    /**
     * Returns the swatch type for the attribute with the passed code and entity type ID.
     *
     * @param integer $entityTypeId  The entity type ID of the EAV attribute to return the swatch type for
     * @param string  $attributeCode The attribute code
     *
     * @return string|null The swatch type (either one of 'text' or 'visual') or NULL, if the attribute is NOT a swatch type
     */
    public function loadSwatchType($entityTypeId, $attributeCode)
    {

        // prepare a unique key for the swatch type from the given code and entity type ID
        $key = sprintf('%d-%s', $entityTypeId, $attributeCode);

        // query whether or not a swatch type has been set
        if ($this->hasSwatchType($key) === false) {
            // load the attribute and the catalog attribute data
            $attribute = $this->loadAttributeByEntityTypeIdAndAttributeCode($entityTypeId, $attributeCode);
            $catalogAttribute = $this->loadCatalogAttribute($attribute[MemberNames::ATTRIBUTE_ID]);

            // query whether or not additional data is available (to figure out if the attribute is a swatch type or not)
            if (is_array($catalogAttribute[MemberNames::ADDITIONAL_DATA]) && $catalogAttribute[MemberNames::ADDITIONAL_DATA] !== null) {
                // unserialize the additional data
                $additionalData = json_decode($catalogAttribute[MemberNames::ADDITIONAL_DATA]);
                // load and return the swatch type (can be either one of 'text' or 'visual')
                return $this->setSwatchType($key, $additionalData[CatalogAttributeObserver::SWATCH_INPUT_TYPE]);
            }
        }

        // return the actual swatch type
        return $this->getSwatchType($key);
    }

    /**
     * Return's the EAV attribute with the passed entity type ID and code.
     *
     * @param integer $entityTypeId  The entity type ID of the EAV attribute to return
     * @param string  $attributeCode The code of the EAV attribute to return
     *
     * @return array The EAV attribute
     */
    protected function loadAttributeByEntityTypeIdAndAttributeCode($entityTypeId, $attributeCode)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeByEntityTypeIdAndAttributeCode($entityTypeId, $attributeCode);
    }

    /**
     * Load's and retur's the EAV catalog attribute with the passed ID.
     *
     * @param string $attributeId The ID of the EAV catalog attribute to return
     *
     * @return array The EAV catalog attribute
     */
    protected function loadCatalogAttribute($attributeId)
    {
        return $this->getAttributeBunchProcessor()->loadCatalogAttribute($attributeId);
    }
}
