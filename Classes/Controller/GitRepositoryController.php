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
 * Class Git Repository Controller
 *
 * @package MaxServ\Typo3Local
 */
class GitRepositoryController extends AbstractController
{
    /**
     * Get Git repository
     *
     * @param string $repository
     *
     * @return boolean
     */
    protected function getGitRepository(
        $repository
    ) {
        return ($repository === 'site-root') ? "" : $repository;
    }

    /**
     * Show git branches
     *
     * @param Request $request
     * @param string $repository
     * @param string $site
     *
     * @return Response
     */
    public function branchAction(Request $request, $repository, $site)
    {
        $path = $this->getSitePath($site);
        $repository = $this->getGitRepository($repository);
        $repositoryPath = $this->getPath($path, $repository);
        if ($this->changeDirectory($repositoryPath)) {
            $this->executeCommand('git branch -r');
        }
        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Checkout a change from a git repository
     *
     * @param Request $request
     * @param string $change
     * @param string $site
     * @param string $repository
     *
     * @return Response
     */
    public function checkoutAction(
        Request $request,
        $change,
        $site,
        $repository
    ) {
        $change = str_replace(array('%21', '!'), '/', $change);
        $path = $this->getSitePath($site);
        $repository = $this->getGitRepository($repository);
        $repositoryPath = $this->getPath($path, $repository);

        if ($this->changeDirectory($repositoryPath)) {
            $this->executeCommand('git checkout ' . escapeshellcmd($change));
        }
        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Clean git repository
     *
     * @param Request $request
     * @param string $site
     * @param string $repository
     *
     * @return Response
     */
    public function cleanAction(Request $request, $site, $repository)
    {
        $path = $this->getSitePath($site);
        $repository = $this->getGitRepository($repository);
        $repositoryPath = $this->getPath($path, $repository);

        if ($this->changeDirectory($repositoryPath)) {
            $this->executeCommand('git clean -df');
        }
        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Fetch changes for git repository
     *
     * @param Request $request
     * @param string $branch
     * @param string $remote
     * @param string $site
     * @param string $repository
     *
     * @return Response
     */
    public function fetchAction(
        Request $request,
        $branch,
        $remote,
        $site,
        $repository
    ) {
        $path = $this->getSitePath($site);

        $repository = $this->getGitRepository($repository);
        $repositoryPath = $this->getPath($path, $repository);

        if ($this->changeDirectory($repositoryPath)) {
            $this->executeCommand('git fetch ' . escapeshellcmd($remote) . ' ' . escapeshellcmd($branch));
        }
        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Get user.email
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getUserEmailAction(Request $request)
    {
        $this->executeCommand('git config --get user.email');
        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Get user.name
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getUserNameAction(Request $request)
    {
        $this->executeCommand('git config --get user.name');
        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Find Git repositories in site root four levels deep
     *
     * @param Request $request
     * @param string $site
     *
     * @return Response
     */
    public function listAction(Request $request, $site)
    {
        $path = $this->getSitePath($site);

        $gitRepositories = glob(
            $path . '{/,/**,/**/**,/**/**/**,/**/**/**/**}/.git',
            GLOB_BRACE | GLOB_ONLYDIR
        );
        $data = array();
        foreach ($gitRepositories as $repository) {
            if (!strstr($repository, 'local.neos.io') && is_dir($repository)) {
                $entry = str_replace(
                    array(
                        $path . '/',
                        '/.git'
                    ),
                    '',
                    $repository
                );
                if ($entry === '') {
                    $entry = 'site-root';
                }
                $data[] = $entry;
            }
        }

        $data = $this->prepareData($request, $data);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Pull git repository
     *
     * @param Request $request
     * @param string $branch
     * @param string $remote
     * @param string $repository
     * @param string $site
     *
     * @return Response
     */
    public function pullAction(
        Request $request,
        $branch,
        $remote,
        $repository,
        $site
    ) {
        $path = $this->getSitePath($site);

        $repository = $this->getGitRepository($repository);
        $repositoryPath = $this->getPath($path, $repository);
        if ($this->changeDirectory($repositoryPath)) {
            $this->executeCommand('git pull ' . escapeshellcmd($remote) . ' ' . escapeshellcmd($branch));
        }

        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Chery Pick a commit
     *
     * @param Request $request
     * @param string $repository
     * @param string $site
     *
     * @return Response
     */
    public function pickAction(Request $request, $repository, $site)
    {
        $fetchUrl = urldecode($request->get('fetchUrl'));
        $fetchUrl = escapeshellcmd($fetchUrl);
        $change = urldecode($request->get('change'));
        $change = escapeshellcmd($change);

        $path = $this->getSitePath($site);

        $repository = $this->getGitRepository($repository);
        $repositoryPath = $this->getPath($path, $repository);
        if ($this->changeDirectory($repositoryPath)) {
            $this->executeCommand('git fetch ' . $fetchUrl . ' ' . $change . ' && git cherry-pick FETCH_HEAD');
        }

        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Reset git repository
     *
     * @param Request $request
     * @param string $branch
     * @param string $remote
     * @param string $repository
     * @param string $site
     *
     * @return Response
     */
    public function resetAction(
        Request $request,
        $branch,
        $remote,
        $repository,
        $site
    ) {
        $path = $this->getSitePath($site);

        $repository = $this->getGitRepository($repository);
        $repositoryPath = $this->getPath($path, $repository);
        if ($this->changeDirectory($repositoryPath)) {
            $this->executeCommand('git reset --hard ' . escapeshellcmd($remote) . '/' . escapeshellcmd($branch));
        }

        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Set user.name
     *
     * @param Request $request
     * @param string $userName
     *
     * @return Response
     */
    public function setUserNameAction(Request $request, $userName)
    {
        $userName = str_replace('%20', ' ', $userName);

        $this->executeCommand('git config --global --replace-all user.name ' . escapeshellarg($userName));
        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Set user.email
     *
     * @param Request $request
     * @param string $userEmail
     *
     * @return Response
     */
    public function setUserEmailAction(Request $request, $userEmail)
    {
        $this->executeCommand('git config --global --replace-all user.email ' . escapeshellarg($userEmail));
        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Return git status
     *
     * @param Request $request
     *
     * @param string $detail
     * @param string $site
     * @param integer $range
     * @param string $repository
     *
     * @return Response
     */
    public function statusAction(
        Request $request,
        $detail,
        $site,
        $range,
        $repository
    ) {
        $commits = array();

        if ($detail === 'oneline') {
            $detail = '--oneline';
        }
        $range = abs((integer)$range);

        $path = $this->getSitePath($site);
        $repository = $this->getGitRepository($repository);
        $repositoryPath = $this->getPath($path, $repository);
        if ($this->changeDirectory($repositoryPath)) {
            if ($detail === '--oneline') {
                $this->executeCommand('git log -' . $range . ' ' . $detail);
                $lines = $this->commandStdout;
                foreach ($lines as $line) {
                    list($sha1, $subject) = explode(' ', $line, 2);
                    $commit = new \stdClass();
                    $commit->sha1 = $sha1;
                    $commit->subject = $subject;
                    $commits[] = $commit;
                }
            } else {
                $this->executeCommand(
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
                $log = implode(PHP_EOL, $this->commandStdout);

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
                    $this->fail(json_last_error_msg());
                }
            }
        }

        $data = $this->prepareData($request, $commits);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }

    /**
     * Show git tags
     *
     * @param Request $request
     * @param string $repository
     * @param string $site
     *
     * @return Response
     */
    public function tagAction(Request $request, $repository, $site)
    {
        $path = $this->getSitePath($site);

        $repository = $this->getGitRepository($repository);
        $repositoryPath = $this->getPath($path, $repository);
        if ($this->changeDirectory($repositoryPath)) {
            $this->executeCommand('git tag');
        }

        $data = $this->prepareData($request);

        $response = new Response($data);
        $response->prepare($request);

        return $response;
    }
}
