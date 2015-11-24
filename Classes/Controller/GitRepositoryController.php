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
     * Show git branches
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function branchAction($arguments = array())
    {
        $format = '';
        $repository = '';
        $site = '';
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['repository'])) {
            $repository = $arguments['repository'];
        }
        if (isset($arguments['site'])) {
            $site = $arguments['site'];
        }

        $path = self::getSitePath($site);

        $repositoryPath = self::getRepositoryPath($path, $repository);
        chdir($repositoryPath);

        $log = self::executeCommand('git branch -r');
        $lines = explode(PHP_EOL, $log);

        if ($format === 'json') {
            self::sendJsonResponse($lines);
        }

        return $lines;
    }

    /**
     * Clean git repository
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function cleanAction($arguments = array())
    {
        $format = '';
        $repository = '';
        $site = '';
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['repository'])) {
            $repository = $arguments['repository'];
        }
        if (isset($arguments['site'])) {
            $site = $arguments['site'];
        }

        $path = self::getSitePath($site);

        $repositoryPath = self::getRepositoryPath($path, $repository);
        chdir($repositoryPath);

        $log = self::executeCommand('git clean -df');
        $lines = explode(PHP_EOL, $log);

        if ($format === 'json') {
            self::sendJsonResponse($lines);
        }

        return $lines;
    }

    /**
     * Fetch changes for git repository
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function fetchAction($arguments = array())
    {
        $branch = '';
        $format = '';
        $remote = '';
        $repository = '';
        $site = '';
        if (isset($arguments['branch'])) {
            $branch = $arguments['branch'];
        }
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['remote'])) {
            $remote = $arguments['remote'];
        }
        if (isset($arguments['repository'])) {
            $repository = $arguments['repository'];
        }
        if (isset($arguments['site'])) {
            $site = $arguments['site'];
        }

        $path = self::getSitePath($site);

        $repositoryPath = self::getRepositoryPath($path, $repository);
        chdir($repositoryPath);

        $log = self::executeCommand('git fetch ' . $remote . ' ' . $branch);
        $lines = explode(PHP_EOL, $log);

        if ($format === 'json') {
            self::sendJsonResponse($lines);
        }

        return $lines;
    }

    /**
     * Get user.email
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function getUserEmailAction($arguments = array())
    {
        $format = '';
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }

        $userEmail = self::executeCommand('git config --get user.email');

        if ($format === 'json') {
            self::sendJsonResponse($userEmail);
        }

        return $userEmail;
    }

    /**
     * Get user.name
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function getUserNameAction($arguments = array())
    {
        $format = '';
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }

        $userName = self::executeCommand('git config --get user.name');

        if ($format === 'json') {
            self::sendJsonResponse($userName);
        }

        return $userName;
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
     * Pull git repository
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function pullAction($arguments = array())
    {
        $branch = '';
        $format = '';
        $remote = '';
        $repository = '';
        $site = '';
        if (isset($arguments['branch'])) {
            $branch = $arguments['branch'];
        }
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['remote'])) {
            $remote = $arguments['remote'];
        }
        if (isset($arguments['repository'])) {
            $repository = $arguments['repository'];
        }
        if (isset($arguments['site'])) {
            $site = $arguments['site'];
        }

        $path = self::getSitePath($site);

        $repositoryPath = self::getRepositoryPath($path, $repository);
        chdir($repositoryPath);

        $log = self::executeCommand('git pull ' . $remote . ' ' . $branch);
        $lines = explode(PHP_EOL, $log);

        if ($format === 'json') {
            self::sendJsonResponse($lines);
        }

        return $lines;
    }

    /**
     * Reset git repository
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function resetAction($arguments = array())
    {
        $branch = '';
        $format = '';
        $remote = '';
        $repository = '';
        $site = '';
        if (isset($arguments['branch'])) {
            $branch = $arguments['branch'];
        }
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['remote'])) {
            $remote = $arguments['remote'];
        }
        if (isset($arguments['repository'])) {
            $repository = $arguments['repository'];
        }
        if (isset($arguments['site'])) {
            $site = $arguments['site'];
        }

        $path = self::getSitePath($site);

        $repositoryPath = self::getRepositoryPath($path, $repository);
        chdir($repositoryPath);

        $log = self::executeCommand('git reset --hard ' . $remote . '/' . $branch);
        $lines = explode(PHP_EOL, $log);

        if ($format === 'json') {
            self::sendJsonResponse($lines);
        }

        return $lines;
    }

    /**
     * Set user.name
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function setUserNameAction($arguments = array())
    {
        $format = '';
        $userName = '';
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['userName'])) {
            $userName = $arguments['userName'];
        }

        $userName = self::executeCommand('git config --global --replace-all user.name "' . addslashes($userName) . '"');

        if ($format === 'json') {
            self::sendJsonResponse($userName);
        }

        return $userName;
    }

    /**
     * Set user.email
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function setUserEmailAction($arguments = array())
    {
        $format = '';
        $userEmail = '';
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['userEmail'])) {
            $userEmail = $arguments['userEmail'];
        }

        $userEmail = self::executeCommand('git config --global --replace-all user.email "' . addslashes($userEmail) . '"');

        if ($format === 'json') {
            self::sendJsonResponse($userEmail);
        }

        return $userEmail;
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
        $commits = array();
        $detail = '';
        $format = '';
        $range = 1;
        $repository = '';
        $site = '';
        $status = 'OK';

        if (isset($arguments['detail'])) {
            if ($arguments['detail'] === 'oneline') {
                $detail = '--oneline';
            }
        }
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['site'])) {
            $site = $arguments['site'];
        }
        if (isset($arguments['range'])) {
            $range = abs((integer)$arguments['range']);
        }
        if (isset($arguments['repository'])) {
            $repository = $arguments['repository'];
        }

        $path = self::getSitePath($site);

        $repositoryPath = self::getRepositoryPath($path, $repository);
        chdir($repositoryPath);

        if ($detail === '--oneline') {
            $log = self::executeCommand('git log -' . $range . ' ' . $detail);
            $lines = explode(PHP_EOL, $log);
            foreach ($lines as $line) {
                list($sha1, $subject) = explode(' ', $line, 2);
                $commit = new \stdClass();
                $commit->sha1 = $sha1;
                $commit->subject = $subject;
                $commits[] = $commit;
            }
        } else {
            $log = self::executeCommand(
                'git log -' . $range . ' --pretty=format:\'<change>%n' .
                '<commit>%H</commit>%n' .
                '<abbreviated_commit>%h</abbreviated_commit>%n' .
                '<tree>%T</tree>%n' .
                '<abbreviated_tree>%t</abbreviated_tree>%n' .
                '<parent>%P</parent>%n' .
                '<abbreviated_parent>%p</abbreviated_parent>>%n' .
                '<subject><![CDATA[%s]]></subject>%n' .
                '<body><![CDATA[%b]]></body>%n' .
                '<author>%n' .
                '  <name>%aN</name>%n' .
                '  <email>%aE</email>%n' .
                '  <date>%aD</date>%n' .
                '</author>%n' .
                '<commiter>%n' .
                '  <name>%cN</name>%n' .
                '  <email>%cE</email>%n' .
                '  <date>%cD</date>%n' .
                '</commiter>%n' .
                '</change>%n' .
                '\''
            );

            if ($range > 1) {
                $log = '<changes>' . $log . '</changes>';
                $xml = simplexml_load_string($log, null, LIBXML_NOCDATA);
                $commitData = json_decode(json_encode($xml));
                $commits = $commitData->change;
            } else {
                $xml = simplexml_load_string($log, null, LIBXML_NOCDATA);
                $commitData = json_decode(json_encode($xml));
                $commits = $commitData;
            }
            if (json_last_error()) {
                $status = 'Error';
                $commit = new \stdClass();
                $commit->message = json_last_error_msg();
                $commits[] = $commit;
            }
        }

        if ($format === 'json') {
            self::sendJsonResponse($commits, $status);
        }

        return $commits;
    }

    /**
     * Show git tags
     *
     * @var array $arguments
     *
     * @return array
     */
    public static function tagAction($arguments = array())
    {
        $format = '';
        $repository = '';
        $site = '';
        if (isset($arguments['format'])) {
            $format = $arguments['format'];
        }
        if (isset($arguments['repository'])) {
            $repository = $arguments['repository'];
        }
        if (isset($arguments['site'])) {
            $site = $arguments['site'];
        }

        $path = self::getSitePath($site);

        $repositoryPath = self::getRepositoryPath($path, $repository);
        chdir($repositoryPath);

        $log = self::executeCommand('git tag');
        $lines = explode(PHP_EOL, $log);

        if ($format === 'json') {
            self::sendJsonResponse($lines);
        }

        return $lines;
    }

}
