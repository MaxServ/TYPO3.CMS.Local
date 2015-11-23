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

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AbstractController
 *
 * @package MaxServ\Typo3Local
 */
class AbstractController
{
    /**
     * Execute Command
     *
     * @param $command
     *
     * @return string
     */
    protected function executeCommand($command)
    {
        $output = '';
        $inputOutput = array();
        $process = proc_open(
            $command,
            array(
                1 => array('pipe', 'w'),
                2 => array('pipe', 'w')
            ),
            $inputOutput
        );

        /* Read output sent to stdout. */
        while (!feof($inputOutput[1])) {
            $output .= fgets($inputOutput[1]);
        }
        /* Read output sent to stderr. */
        while (!feof($inputOutput[2])) {
            $output .= fgets($inputOutput[2]);
        }

        fclose($inputOutput[1]);
        fclose($inputOutput[2]);
        proc_close($process);

        return $output;
    }

    /**
     * Get site path
     *
     * @var string $site
     *
     * @return array
     */
    protected static function getSitePath($site = '')
    {
        // Prevent sneaky back path hacks
        $site = basename($site);

        return realpath(REVIEW_DOCUMENT_ROOT . '/../' . $site);
    }

    /**
     * Send JSON response
     *
     * @var object|string|array $data
     * @var string $status
     *
     * @return array
     */
    protected static function sendJsonResponse($data = '', $status = 'OK')
    {
        $object = new \stdClass();
        $object->status = $status;
        $object->data = $data;
        $response = new JsonResponse($object);
        $response->send();
    }
}
