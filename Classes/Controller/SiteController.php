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
 * Class SiteController
 *
 * @package MaxServ\Typo3Local
 */
class SiteController extends AbstractController
{

    /**
     * Get TYPO3 sites
     *
     * @return array
     */
    public function getTypo3Sites()
    {
        $sites = scandir(REVIEW_DOCUMENT_ROOT . '/..');
        $exclude = array(
            '.',
            '..',
            'html',
            'local.typo3.org'
        );
        $typo3Sites = array();
        foreach ($sites as $site) {
            $pathToTypo3 = REVIEW_DOCUMENT_ROOT . '/../' . $site . '/typo3/';
            if (!in_array($site, $exclude)) {
                if (!strstr($site, 'local.neos.io')) {
                    if (is_dir($pathToTypo3)) {
                        $typo3Sites[] = $site;
                    }
                    if (is_link($pathToTypo3) &&
                        readlink($pathToTypo3) &&
                        is_dir(readlink($pathToTypo3))
                    ) {
                        $typo3Sites[] = $site;
                    }
                }
            }

        }

        return $typo3Sites;
    }

    /**
     * Find TYPO3 sites
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $data = $this->getTypo3Sites();
        $data = $this->prepareData($request, $data);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }
}
