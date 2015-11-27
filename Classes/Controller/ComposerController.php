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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ComposerController
 *
 * @package MaxServ\Typo3Local
 */
class ComposerController extends AbstractController
{
    /**
     * Diagnose
     *
     * @param Request $request
     * @param string $path
     * @param string $site
     * @param string $command
     * @param string $devMode
     *
     * @return Response
     */
    protected function executeComposerCommand(
        Request $request,
        $path,
        $site,
        $command,
        $devMode = 'no-dev'
    ) {
        $devSwitch = '';
        if (in_array($command, array('install', 'update'))) {
            if ($devMode === 'no-dev') {
                $devSwitch = '--no-dev';
            }
        }
        $path = ($path === 'root') ? '' : $path;

        $sitePath = $this->getSitePath($site);
        $fullPath = $this->getPath($sitePath, $path);
        if ($this->changeDirectory($fullPath)) {
            if ($request->getRequestFormat() === 'json') {
                $this->executeCommand('composer --no-ansi ' . $command . ' ' . $devSwitch);
                $data = $this->prepareData($request);

                $response = new Response($data);
                $response->prepare($request);

                return $response;
            } else {
                $response = $this->executeLiveCommand('composer --no-ansi ' . $command . ' ' . $devSwitch);

                return $response;
            }
        }
        $response = new Response();
        $response->prepare($request);

        return $response;
    }

    /**
     * Diagnose
     *
     * @param Request $request
     * @param string $path
     * @param string $site
     *
     * @return Response
     */
    public function diagnoseAction(Request $request, $path, $site)
    {
        return $this->executeComposerCommand($request, $path, $site,
            'diagnose');
    }

    /**
     * Dump autoloading classes
     *
     * @param Request $request
     * @param string $path
     * @param string $site
     *
     * @return Response
     */
    public function dumpAutoloadAction(
        Request $request,
        $path,
        $site
    ) {
        return $this->executeComposerCommand($request, $path, $site,
            'dump-autoload');
    }

    /**
     * Install packages
     *
     * @param Request $request
     * @param string $path
     * @param string $site
     * @param string $devMode
     *
     * @return Response
     */
    public function installAction(Request $request, $path, $site, $devMode)
    {
        return $this->executeComposerCommand($request, $path, $site,
            'install', $devMode);
    }

    /**
     * Update packages
     *
     * @param Request $request
     * @param string $path
     * @param string $site
     * @param string $devMode
     *
     * @return Response
     */
    public function updateAction(Request $request, $path, $site, $devMode)
    {
        return $this->executeComposerCommand($request, $path, $site, 'update',
            $devMode);
    }
}
