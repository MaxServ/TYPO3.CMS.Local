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
 * Class GitRepositoryController
 *
 * @package MaxServ\Typo3Local
 */
class GitRepositoryController extends AbstractController
{
    /**
     * Get repository path
     *
     * Slashes in the url are encoded as exclamation marks
     *
     * @var string $basePath
     * @var string $repository
     *
     * @return array
     */
    protected static function getRepositoryPath($basePath = '', $repository = '')
    {
        $repository = str_replace(array('%21', '!'), '/', $repository);

        $path = realpath($basePath . '/' . $repository);
        if (!strpos($path, $basePath) === 0) {
            $path = $basePath;
        }
        return $path;
    }

    /**
     * Find Git repositories in site root four levels deep
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function listAction($arguments = array())
    {
        $format = '';
        $site = '';
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['site'])) {
            $site = $arguments['site'];
        }

        $path = self::getSitePath($site);

        $gitRepositories = glob(
            $path . '/{**,**/**,**/**/**,**/**/**/**}/.git',
            GLOB_BRACE | GLOB_ONLYDIR
        );
        $filteredRepositories = array();
        foreach ($gitRepositories as $repository) {
            if (!strstr($repository, 'local.neos.io') && is_dir($repository)) {
                $filteredRepositories[] = str_replace(
                    array(
                        $path . '/',
                        '/.git'
                    ),
                    '',
                    $repository
                );
            }
        }

        if ($format === 'json') {
            self::sendJsonResponse($filteredRepositories);
        }

        return $filteredRepositories;
    }

    /**
     * Return git status
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function statusAction($arguments = array())
    {
        $format = '';
        $repository = '';
        $site = '';
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['site'])) {
            $site = $arguments['site'];
        }
        if (isset($arguments['repository'])) {
            $repository = $arguments['repository'];
        }

        $path = self::getSitePath($site);

        $repositoryPath = self::getRepositoryPath($path, $repository);
        chdir($repositoryPath);
        $log = self::executeCommand('git log -1 --oneline');

        if ($format === 'json') {
            self::sendJsonResponse($log);
        }

        return $log;
    }
}
