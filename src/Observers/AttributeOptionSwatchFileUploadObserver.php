<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionSwatchFileUploadObserver
 *
 * @author    Marcus Döllerer <m.doellerer@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @link      https://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Observers;

use TechDivision\Import\Attribute\Utils\ConfigurationKeys;
use TechDivision\Import\Attribute\Utils\MemberNames;
use TechDivision\Import\Attribute\Utils\SwatchTypes;

/**
 * Abstract attribute observer that handles files of visual swatches during import.
 *
 * @author    Marcus Döllerer <m.doellerer@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-attribute
 * @link      http://www.techdivision.com
 */
class AttributeOptionSwatchFileUploadObserver extends AttributeOptionSwatchUpdateObserver
{

    /**
     * Process the observer's business logic.
     *
     * @return void
     */
    protected function process()
    {

        // skip this step if the configuration value 'copy-images' is undefined or set to 'false'
        if ($this->getSubject()->getConfiguration()->hasParam(ConfigurationKeys::COPY_IMAGES) === false ||
            $this->getSubject()->getConfiguration()->getParam(ConfigurationKeys::COPY_IMAGES) === false
        ) {
            return;
        }

        // initialize the option swatch attribute
        $attributeOptionSwatch = $this->initializeAttribute(array(
            MemberNames::OPTION_ID => $this->getLastOptionId()
        ));

        // skip this step for color swatches and text swatches
        if (isset($attributeOptionSwatch[MemberNames::TYPE]) &&  $attributeOptionSwatch[MemberNames::TYPE] === SwatchTypes::IMAGE) {
            // upload the file to the configured directory
            $imagePath = $this->getSubject()->uploadFile($attributeOptionSwatch[MemberNames::VALUE]);

            // inject the new image path and update the attribute option swatch
            $attributeOptionSwatch['value'] = $imagePath;
            $this->getAttributeBunchProcessor()->persistAttributeOptionSwatch($attributeOptionSwatch);

            // add debug log entry
            $this->getSubject()
                 ->getSystemLogger()
                 ->debug(
                     sprintf(
                         'Successfully copied image %s for swatch with id %s',
                         $imagePath,
                         $attributeOptionSwatch[MemberNames::SWATCH_ID]
                     )
                 );
        }
    }
}
