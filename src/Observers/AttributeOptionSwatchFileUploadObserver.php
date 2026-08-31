<?php

/**
 * TechDivision\Import\Attribute\Observers\AttributeOptionSwatchFileUploadObserver
 *
 * @author    Marcus Döllerer <m.doellerer@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @link      https://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Observers;

use TechDivision\Import\Attribute\Utils\ColumnKeys;
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

        // skip this step for color swatches and text swatches - read the raw CSV filename reference directly from the
        // row, independent of whatever the .update observer did (or, for existing image swatches, deliberately did NOT)
        // persist for this row
        $type = $this->getValue(ColumnKeys::SWATCH_TYPE);

        if ($type === null || (int) $type !== SwatchTypes::IMAGE) {
            return;
        }

        $rawValue = $this->getValue(ColumnKeys::SWATCH_VALUE);

        // load the current DB state (swatch_id + the last successfully uploaded, stable path)
        $attributeOptionSwatch = $this->initializeAttribute([MemberNames::OPTION_ID => $this->getLastOptionId()]);

        // upload the file (or resolve the already up-to-date, stable path for it)
        $imagePath = $this->getSubject()->uploadFile($rawValue);

        // skip the persist if the row already exists and already has this exact path stored - the genuine "unchanged"
        // case, only reliably detectable here because "override-images" makes uploadFile()'s result stable across
        // separate runs and the .update observer no longer overwrites it with the (never-matching) raw filename
        // beforehand
        if (isset($attributeOptionSwatch[MemberNames::SWATCH_ID])
            && isset($attributeOptionSwatch[MemberNames::VALUE])
            && $attributeOptionSwatch[MemberNames::VALUE] === $imagePath) {
            return;
        }

        // inject the new image path and type, then persist the attribute option swatch
        $attributeOptionSwatch[MemberNames::TYPE] = SwatchTypes::IMAGE;
        $attributeOptionSwatch[MemberNames::VALUE] = $imagePath;
        $this->getAttributeBunchProcessor()->persistAttributeOptionSwatch($attributeOptionSwatch);

        // add debug log entry
        $this->getSubject()->getSystemLogger()->debug(
            sprintf(
                'Successfully copied image %s for swatch with id %s',
                $imagePath,
                $attributeOptionSwatch[MemberNames::SWATCH_ID] ?? 'n/a'
            )
        );
    }
}
