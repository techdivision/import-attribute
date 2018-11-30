<?php

/**
 * TechDivision\Import\Attribute\Callbacks\SwatchTypeLoader
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

namespace TechDivision\Import\Attribute\Callbacks;

use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Observers\CatalogAttributeObserver;
use TechDivision\Import\Attribute\Services\AttributeBunchProcessorInterface;

/**
 * A loader implementation that loads the swatch type for the attribute with the given code.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
     * Query whether or not a swatch type for the attribute with the given code has already been loaded.
     *
     * @param string $attributeCode The attribute code to query for
     *
     * @return boolean TRUE if the swatch type has been loaded, else FALSE
     */
    protected function hasSwatchType($attributeCode)
    {
        return isset($this->swatchType[$attributeCode]);
    }

    /**
     * Set the swatch type for the given attribute code.
     *
     * @param string $attributeCode The attribute code to set the swatch type for
     * @param string $swatchType    The swatch type of the attribute with the given code
     *
     * @return void
     */
    protected function setSwatchType($attributeCode, $swatchType)
    {
        $this->swatchType[$attributeCode] = $swatchType;
    }

    /**
     * Returns the swatch type for the passed attribute code.
     *
     * @param string $attributeCode The attribute to return the swatch type for
     *
     * @return string|null The swatch type of the attribute with the passed code or NULL
     */
    protected function getSwatchType($attributeCode)
    {

        // reutrn the swatch type, if available
        if (isset($this->swatchType[$attributeCode])) {
            return $this->swatchType[$attributeCode];
        }
    }

    /**
     * The attribute code to load the swatch type for.
     *
     * @param string $attributeCode The attribute code
     *
     * @return string|null The swatch type (either one of 'text' or 'visual') or NULL, if the attribute is NOT a swatch type
     */
    public function loadSwatchType($attributeCode)
    {

        // query whether or not a swatch type has been set
        if ($this->hasSwatchType($attributeCode) === false) {
            // load the attribute and the catalog attribute data
            $attribute = $this->loadAttributeByAttributeCode($attributeCode);
            $catalogAttribute = $this->loadCatalogAttribute($attribute[MemberNames::ATTRIBUTE_ID]);

            // query whether or not additional data is available (to figure out if the attribute is a swatch type or not)
            if (is_array($catalogAttribute[MemberNames::ADDITIONAL_DATA]) && $catalogAttribute[MemberNames::ADDITIONAL_DATA] !== null) {
                // unserialize the additional data
                $additionalData = json_decode($catalogAttribute[MemberNames::ADDITIONAL_DATA]);
                // load and return the swatch type (can be either one of 'text' or 'visual')
                return $this->setSwatchType($attributeCode, $additionalData[CatalogAttributeObserver::SWATCH_INPUT_TYPE]);
            }
        }

        // return the actual swatch type
        return $this->getSwatchType($attributeCode);
    }

    /**
     * Load's and return's the EAV attribute with the passed code.
     *
     * @param string $attributeCode The code of the EAV attribute to load
     *
     * @return array The EAV attribute
     */
    protected function loadAttributeByAttributeCode($attributeCode)
    {
        return $this->getAttributeBunchProcessor()->loadAttributeByAttributeCode($attributeCode);
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
