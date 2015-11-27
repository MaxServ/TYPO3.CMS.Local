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
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Process\Exception\ProcessFailedException;
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
    protected $commandStderr = array();

    /**
     * Output messages
     *
     * @var array
     */
    protected $commandStdout = array();

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
     *
     * @return boolean
     */
    protected function executeCommand(
        $command
    ) {
        $process = new Process($command);
        $process->setTimeout(600);
        $process->setIdleTimeout(300);

        $process->run();
        if (!$process->isSuccessful()) {
            $this->fail();
        }
        $stdOut = rtrim($process->getErrorOutput(), PHP_EOL);
        if (strlen($stdOut)) {
            $this->commandStderr = explode(PHP_EOL, $stdOut);
        }
        $stdErr = rtrim($process->getOutput(), PHP_EOL);
        if (strlen($stdErr)) {
            $this->commandStdout = explode(PHP_EOL, $stdErr);
        }

        return $process->isSuccessful();
    }

    /**
     * Execute Live Command
     *
     * @param string $command
     *
     * @return StreamedResponse
     */
    protected function executeLiveCommand(
        $command
    ) {
        $response = new StreamedResponse();
        $process = new Process($command);
        $process->setTimeout(600);
        $process->setIdleTimeout(300);
        $response->setCallback(function () use ($process) {
            try {
                $process->mustRun(function ($type, $buffer) {
                    if (Process::ERR === $type) {
                        $this->fail();
                        echo $buffer;
                        ob_flush();
                        flush();
                    } else {
                        echo $buffer;
                        ob_flush();
                        flush();
                    }
                });
            } catch (ProcessFailedException $e) {
                $this->fail($e->getMessage());
            }
        });

        return $response;
    }

    /**
     * Fail the command
     *
     * @param string $message
     *
     * @return void
     */
    protected function fail($message = null)
    {
        $this->commandStatus = self::STATUS_ERROR;
        if ($message !== null) {
            $this->commandStderr[] = $message;
        }
    }

    /**
     * Get path
     *
     * Slashes in the url are encoded as exclamation marks
     *
     * @var string $basePath
     * @var string $path
     *
     * @return array
     */
    protected function getPath($basePath = '', $path = '')
    {
        $path = str_replace(array('%21', '!'), '/', $path);

        $fullPath = realpath($basePath . '/' . $path);
        if (!strpos($fullPath, $basePath) === 0) {
            $fullPath = $basePath;
        }

        return $fullPath;
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
    protected function prepareData(Request $request, $data = null)
    {
        if ($request->getRequestFormat() === 'json') {
            $json = new \stdClass();
            $json->status = $this->commandStatus;
            if ($data) {
                $json->stdout = $data;
            } elseif (count($this->commandStdout)) {
                $json->stdout = $this->commandStdout;
            }
            if (count($this->commandStderr)) {
                $json->stderr = $this->commandStderr;
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
