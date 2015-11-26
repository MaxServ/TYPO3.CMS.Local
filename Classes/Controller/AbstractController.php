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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;

/**
 * Class AbstractController
 *
 * @package MaxServ\Typo3Local
 */
class AbstractController extends Controller
{

    /**
     * Error status code
     *
     * @var string
     */
    const STATUS_ERROR = 'Error';

    /**
     * OK status code
     *
     * @var string
     */
    const STATUS_OK = 'OK';

    /**
     * Command status
     *
     * @var string
     */
    protected $commandStatus = self::STATUS_OK;

    /**
     * Error messages
     *
     * @var array
     */
    protected $errorMessages = array();

    /**
     * TYPO3 Manager version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Execute Command
     *
     * @param string $command
     * @param string $mode
     *
     * @return boolean|string
     */
    protected function executeCommand($command, $mode = 'asynchronous')
    {
        $process = new Process($command);
        if ($mode === 'live') {
            $process->run(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    $this->fail($buffer);
                } else {
                    echo 'OUT ' . $buffer;
                }
            });
            if ($process->isSuccessful()) {
                return true;
            }
        } else {
            $process->run();
            if ($process->isSuccessful()) {
                return rtrim($process->getOutput(), PHP_EOL);
            }
        }

        $this->fail($process->getErrorOutput());

        return false;
    }

    /**
     * Fail the command
     *
     * @param string $message
     *
     * @return void
     */
    protected function fail($message)
    {
        $this->commandStatus = self::STATUS_ERROR;
        $this->errorMessages[] = $message;
    }

    /**
     * Get site path
     *
     * @var string $site
     *
     * @return array
     */
    protected function getSitePath($site = '')
    {
        // Prevent sneaky back path hacks
        $site = basename($site);

        return realpath(REVIEW_DOCUMENT_ROOT . '/../' . $site);
    }

    /**
     * Change to directory
     *
     * @var string $directory
     *
     * @return boolean
     */
    protected function changeDirectory($directory)
    {
        if (!is_dir($directory)) {
            $this->fail('Directory does not exist.');
        }

        return chdir($directory);
    }

    /**
     * Prepare data
     *
     * @param Request $request
     * @param mixed $data
     *
     * @return mixed $data
     */
    protected function prepareData(Request $request, $data)
    {
        if ($request->getRequestFormat() === 'json') {
            $json = new \stdClass();
            $json->status = $this->commandStatus;
            if ($this->commandStatus === self::STATUS_ERROR) {
                $json->data = $this->errorMessages;
            } else {
                $json->data = $data;
            }
            $data = json_encode($json);
        } else {
            if (is_array($data)) {
                $data = implode(PHP_EOL, $data);
            }
        }

        return $data;
    }
}
