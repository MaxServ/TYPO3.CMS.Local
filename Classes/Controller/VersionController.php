<?php
namespace MaxServ\Typo3Local;

/**
 *  Copyright notice
 *
 *  ⓒ 2015 Michiel Roos <michiel@maxserv.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is free
 *  software; you can redistribute it and/or modify it under the terms of the
 *  GNU General Public License as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful, but
 *  WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 *  or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */

/**
 * Class VersionController
 *
 * @package MaxServ\Typo3Local
 */
class VersionController extends AbstractController
{
    /**
     * Find TYPO3 sites
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function listAction($arguments = array())
    {
        $format = '';
        if (isset($arguments['format'])) {
            $format = $arguments['format'] ?: '';
        }

        if ($format === 'json') {
            self::sendJsonResponse(self::$version);
        }

        return self::$version;
    }
}
