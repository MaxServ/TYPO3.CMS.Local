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
 * Class PhpunitController
 *
 * @package MaxServ\Typo3Local
 */
class PhpunitController extends AbstractController
{
    /**
     * Run functional tests
     *
     * @param Request $request
     * @param string $site
     *
     * @return Response
     */
    public function functionalAction(Request $request, $site)
    {
        $path = $this->getSitePath($site);
        if ($this->changeDirectory($path)) {
            $databaseName = str_replace('.', '_', $site);
            if ($request->getRequestFormat() === 'json') {
                $this->executeCommand(
                    'typo3DatabaseName="' . $databaseName . '" typo3DatabaseUsername="root" typo3DatabasePassword="supersecret" typo3DatabaseHost="localhost" ./bin/phpunit -c typo3_src/typo3/sysext/core/Build/FunctionalTests.xml'
                );
                $data = $this->prepareData($request);

                $response = new Response($data);
                $response->prepare($request);

                return $response;
            } else {
                $response = $this->executeLiveCommand(
                    'typo3DatabaseName="' . $databaseName . '" typo3DatabaseUsername="root" typo3DatabasePassword="supersecret" typo3DatabaseHost="localhost" ./bin/phpunit -c typo3_src/typo3/sysext/core/Build/FunctionalTests.xml'
                );

                return $response;
            }
        }

        $response = new Response();
        $response->prepare($request);

        return $response;
    }

    /**
     * Run unit tests
     *
     * @param Request $request
     * @param string $site
     *
     * @return Response
     */
    public function unitAction(Request $request, $site)
    {
        $path = $this->getSitePath($site);
        if ($this->changeDirectory($path)) {
            if ($request->getRequestFormat() === 'json') {
                $this->executeCommand(
                    './bin/phpunit -c typo3_src/typo3/sysext/core/Build/UnitTests.xml'
                );
                $data = $this->prepareData($request);

                $response = new Response($data);
                $response->prepare($request);

                return $response;
            } else {
                $response = $this->executeLiveCommand(
                    './bin/phpunit -c typo3_src/typo3/sysext/core/Build/UnitTests.xml'
                );

                return $response;
            }
        }

        $response = new Response();
        $response->prepare($request);

        return $response;
    }
}
