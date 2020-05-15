<?php

/**
 * TechDivision\Import\Attribute\Utils\ConfigurationKeys
 *
 * @author    Marcus Döllerer <m.doellerer@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @link      https://www.techdivision.com
 */

namespace TechDivision\Import\Attribute\Utils;

/**
 * Utility class containing the configuration keys.
 *
 * @author    Marcus Döllerer <m.doellerer@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product
 * @link      https://www.techdivision.com
 */
class ConfigurationKeys extends \TechDivision\Import\Utils\ConfigurationKeys
{

    /**
     * Name for the column 'media-directory'.
     *
     * @var string
     */
    const MEDIA_DIRECTORY = 'media-directory';

    /**
     * Name for the column 'images-file-directory'.
     *
     * @var string
     */
    const IMAGES_FILE_DIRECTORY = 'images-file-directory';

    /**
     * Name for the column 'copy-images'.
     *
     * @var string
     */
    const COPY_IMAGES = 'copy-images';
}
