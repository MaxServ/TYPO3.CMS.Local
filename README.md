# TYPO3.CMS.Local
Manage locally installed TYPO3 sites. This is a site to help manage locally installed TYPO3 sites in review and development boxes built by [TYPO3 Packer](https://github.com/Tuurlijk/TYPO3.Packer).

This can replace the existing default page on [http://local.typo3.org](http://local.typo3.org) and can be used by third party code (like Chrome extensions) to get information about running sites, exising git repositories etc.

## Installation
Clone this repository and then do a:
`composer install`

## Working requests

### Composer controller

#### Composer diagnose
[`/composer/diagnose/review.local.typo3.org/typo3_src`](http://local.typo3.org/composer/diagnose/review.local.typo3.org/typo3_src)
```
{
  "status": "Error",
  "stdout": [
    "Checking composer.json: OK",
    "Checking platform settings: OK",
    "Checking git settings: OK",
    "Checking http connectivity to packagist: OK",
    "Checking https connectivity to packagist: OK",
    "Checking github.com rate limit: OK",
    "Checking disk free space: OK",
    "Checking composer version: FAIL",
    "You are not running the latest version, run `composer self-update` to update"
  ]
}
```

#### Composer dump autoload
[`/composer/dumpautoload/review.local.typo3.org/typo3_src`](http://local.typo3.org/composer/dumpautoload/review.local.typo3.org/typo3_src)
```
{
  "status": "OK",
  "stdout": [
    "Generating empty class alias map file",
    "Inserting class alias loader into main autoload.php file"
  ],
  "stderr": [
    "Generating optimized autoload files"
  ]
}
```

### Comoser install
[`/composer/install/review.local.typo3.org/typo3_src`](http://local.typo3.org/composer/install/review.local.typo3.org/typo3_src)
```
{
  "status": "OK",
  "stdout": [
    "Generating empty class alias map file",
    "Inserting class alias loader into main autoload.php file"
  ],
  "stderr": [
    "Loading composer repositories with package information",
    "Installing dependencies from lock file",
    "Nothing to install or update",
    "Generating optimized autoload files"
  ]
}
```

### Comoser update
[`/composer/update/review.local.typo3.org/typo3_src`](http://local.typo3.org/composer/update/review.local.typo3.org/typo3_src)

```
{
  "status": "OK",
  "stdout": [
    "Generating empty class alias map file",
    "Inserting class alias loader into main autoload.php file"
  ],
  "stderr": [
    "Loading composer repositories with package information",
    "Updating dependencies",
    "  - Removing symfony/console (v2.7.4)",
    "  - Installing symfony/console (v2.7.7)",
    "    Loading from cache",
    "",
    "  - Removing symfony/finder (v2.7.4)",
    "  - Installing symfony/finder (v2.7.7)",
    "    Loading from cache",
    "",
    "Writing lock file",
    "Generating optimized autoload files"
  ]
}
```

### Site controller

#### List
[`/site/list`](http://local.typo3.org/site/list)

Will return a json object containing the TYPO3 sites found in `/var/www`.
```
{
  "status": "OK",
  "stdout": [
    "6.2.local.typo3.org",
    "7.6.local.typo3.org",
    "dev-master.local.typo3.org",
    "review.local.typo3.org"
  ]
}
```

### Git repository controller
Warning! Git returns some of it's output on stderr instead of on stdout.

### Branch
[`/git/branch/review.local.typo3.org/typo3_src`](http://local.typo3.org/git/branch/review.local.typo3.org/typo3_src)

Will return the list of available remote branches.
```
{
  "status": "OK",
  "stdout": [
    "  composer/TYPO3_3-6",
    "  composer/TYPO3_3-7",
    "  composer/TYPO3_3-8",
    "  composer/TYPO3_4-0",
    "  composer/TYPO3_4-1",
    "  composer/TYPO3_4-2",
    "  composer/TYPO3_4-3",
    "  composer/TYPO3_4-4",
    "  composer/TYPO3_4-5",
    "  composer/TYPO3_4-6",
    . . .
  ]
}
```
### Checkout

### By change
[`/git/checkout/review.local.typo3.org/typo3_src/a2585ba`](http://local.typo3.org/git/checkout/review.local.typo3.org/typo3_src/a2585ba)

Will checkout the given hash.

```
{
  "status": "OK",
  "stderr": [
    "Note: checking out 'a2585ba'.",
    "",
    "You are in 'detached HEAD' state. You can look around, make experimental",
    "changes and commit them, and you can discard any commits you make in this",
    "state without impacting any branches by performing another checkout.",
    "",
    "If you want to create a new branch to retain commits you create, you may",
    "do so (now or later) by using -b with the checkout command again. Example:",
    "",
    "  git checkout -b new_branch_name",
    "",
    "HEAD is now at a2585ba... [RELEASE] Release of TYPO3 7.6.0"
  ]
}
```

#### By branch
[`/git/checkout/review.local.typo3.org/typo3_src/origin!master`](http://local.typo3.org/git/checkout/review.local.typo3.org/typo3_src/origin!master)

Will checkout the given branch.

```
{
  "status": "OK",
  "stderr": [
    "Previous HEAD position was a2585ba... [RELEASE] Release of TYPO3 7.6.0",
    "HEAD is now at 10b2f0c... [BUGFIX] Save parents localized uid as child reference"
  ]
}
```

#### By tag
[`/git/checkout/review.local.typo3.org/typo3_src/7.6.0`](http://local.typo3.org/git/checkout/review.local.typo3.org/typo3_src/7.6.0)

Will checkout the given tag.

```
{
  "status": "OK",
  "stderr": [
    "Previous HEAD position was 10b2f0c... [BUGFIX] Save parents localized uid as child reference",
    "HEAD is now at a2585ba... [RELEASE] Release of TYPO3 7.6.0"
  ]
}
```

### Cherry Pick
[`/git/pick/review.local.typo3.org/typo3_src?fetchUrl=https%3A%2F%2Freview.typo3.org%2FPackages%2FTYPO3.CMS&change=refs%2Fchanges%2F83%2F43483%2F13%0A`](http://local.typo3.org/git/pick/review.local.typo3.org/typo3_src?fetchUrl=https%3A%2F%2Freview.typo3.org%2FPackages%2FTYPO3.CMS&change=refs%2Fchanges%2F83%2F43483%2F13%0A)

Will cherry pick from the given fetchUrl using the given change. Both the fetchUrl and the change should be urlEncoded.

```
{
  "status": "OK",
  "stdout": [
    "[detached HEAD c7b3e50] [FEATURE] Introduce Session Framework",
    " Author: Mathias Schreiber <mathias.schreiber@wmdb.de>",
    " 15 files changed, 1218 insertions(+), 106 deletions(-)",
    " create mode 100644 typo3/sysext/core/Classes/Session/Backend/DatabaseSessionBackend.php",
    " create mode 100644 typo3/sysext/core/Classes/Session/Backend/FileSessionBackend.php",
    " create mode 100644 typo3/sysext/core/Classes/Session/Backend/RedisSessionBackend.php",
    " create mode 100644 typo3/sysext/core/Classes/Session/Backend/SessionBackendInterface.php",
    " create mode 100644 typo3/sysext/core/Classes/Session/SessionManager.php",
    " create mode 100644 typo3/sysext/core/Documentation/Changelog/master/Feature-70316-IntroduceSessionFramework.rst",
    " create mode 100644 typo3/sysext/core/Tests/Functional/Session/Backend/AbstractSessionBackendTest.php",
    " create mode 100644 typo3/sysext/core/Tests/Functional/Session/Backend/DatabaseSessionBackendTest.php",
    " create mode 100644 typo3/sysext/core/Tests/Functional/Session/Backend/FileSessionBackendTest.php",
    " create mode 100644 typo3/sysext/core/Tests/Unit/Session/SessionManagerTest.php"
  ],
  "stderr": [
    "From https://review.typo3.org/Packages/TYPO3.CMS",
    " * branch            refs/changes/83/43483/13 -> FETCH_HEAD"
  ]
}
```

### Clean
[`/git/clean/review.local.typo3.org/typo3_src`](http://local.typo3.org/git/clean/review.local.typo3.org/typo3_src)

Will clean a git repository.

```
{
  "status": "OK",
  "stdout": [
    "Removing vendor/"
  ]
}
```

### Fetch
[`/git/fetch/review.local.typo3.org/typo3_src`](http://local.typo3.org/git/fetch/review.local.typo3.org/typo3_src)

Will fetch changes from origin/master.

```
{
  "status": "OK",
  "stderr": [
    "remote: Counting objects: 61, done.",
    "remote: Compressing objects: 100% (36/36), done.",
    "remote: Total 37 (delta 29), reused 0 (delta 0)",
    "Unpacking objects: 100% (37/37), done.",
    "From https://git.typo3.org/Packages/TYPO3.CMS",
    "   1053983..d050733  TYPO3_6-2  -> origin/TYPO3_6-2"
  ]
}
```
You may specify remote and branch parameters:
[`/git/fetch/review.local.typo3.org/typo3_src/{remote}/{branch}`](http://local.typo3.org/git/fetch/review.local.typo3.org/typo3_src/{remote}/{branch})

### Get user.email
[`/git/getuseremail`](http://local.typo3.org/git/getuseremail)

Will fetch user.email configuration value.
```
{
  "status": "OK",
  "stdout": [
    "michiel@noreply.org"
  ]
}
```
### Set user.email
[`/git/setuseremail/michiel@noreply.org`](http://local.typo3.org/git/setuseremail/michiel@noreply.org)

Will globally set the user.email configuration value.
```
{
  "status": "OK"
}
```
### Get user.name
[`/git/getusername`](http://local.typo3.org/git/getusername)

Will fetch user.name configuration value.
```
{
  "status": "OK",
  "stdout": "Michiel Roos"
}
```
### Set user.name
[`/git/setusername/Pietje%20Puk`](http://local.typo3.org/git/setusername/Pietje%20Puk)

Will globally set the user.name configuration value.
```
{
  "status": "OK"
}
```
### List
[`/git/list/review.local.typo3.org`](http://local.typo3.org/git/list/review.local.typo3.org)

Will return a json object containing all the git repositories found in the given site.

```
{
  "status": "OK",
  "stdout": [
    "typo3_src",
    "typo3conf/ext/accountmanagement",
    "typo3conf/ext/distributionmanagement",
    "typo3conf/ext/icon_api",
    "typo3conf/ext/typo3_console",
    "vendor/cogpowered/finediff",
    "vendor/doctrine/instantiator",
    "vendor/mikey179/vfsStream",
    "vendor/pear/http_request2",
    "vendor/pear/net_url2",
    "vendor/pear/pear_exception",
    "vendor/phpdocumentor/reflection-docblock",
    "vendor/phpspec/prophecy",
    "vendor/phpunit/php-code-coverage",
    "vendor/phpunit/php-file-iterator",
    "vendor/phpunit/php-text-template",
    "vendor/phpunit/php-timer",
    "vendor/phpunit/php-token-stream",
    "vendor/phpunit/phpunit-mock-objects",
    "vendor/phpunit/phpunit",
    "vendor/phpwhois/idna-convert",
    "vendor/psr/http-message",
    "vendor/psr/log",
    "vendor/sebastian/comparator",
    "vendor/sebastian/diff",
    "vendor/sebastian/environment",
    "vendor/sebastian/exporter",
    "vendor/sebastian/global-state",
    "vendor/sebastian/recursion-context",
    "vendor/sebastian/version",
    "vendor/swiftmailer/swiftmailer",
    "vendor/symfony/console",
    "vendor/symfony/finder",
    "vendor/symfony/yaml",
    "vendor/typo3/class-alias-loader",
    "vendor/typo3/cms-composer-installers"
  ]
}
```

### Reset
[`/git/reset/review.local.typo3.org/typo3_src`](http://local.typo3.org/git/reset/review.local.typo3.org/typo3_src)

Will hard reset the git repository to origin/master.

```
{
  "status": "OK",
  "stdout": [
    "HEAD is now at 07481bd [BUGFIX] Show title in button popup with correct encoding"
  ]
}
```
You may specify remote and branch parameters:
[`/git/reset/review.local.typo3.org/typo3_src/{remote}/{branch}`](http://local.typo3.org/git/reset/review.local.typo3.org/typo3_src/{remote}/{branch})

### Status
[`/git/status/review.local.typo3.org/vendor!mikey179!vfsStream`](http://local.typo3.org/git/status/review.local.typo3.org/vendor!mikey179!vfsStream)

Will return a json object containing the latest commit sha1 and message. Note that the slashes in the repository are encoded as exclamation marks in the request. You can either use '!' or '%21' to encode the '/' characters.
```
{
  "status": "OK",
  "stdout": [
    {
      "sha1": "73bcb60",
      "subject": "update to latest travis changes"
    }
  ]
}
```

[`/git/status/review.local.typo3.org/typo3_src/3`](http://local.typo3.org/git/status/review.local.typo3.org/typo3_src/3)
Will return the last 3 commit messages in oneline format.
```
{
	"status": "OK",
	"stdout": [
		{
			"sha1": "848b2fc",
			"subject": "[BUGFIX] RTE fields do not handle eval validation like required"
		},
		{
			"sha1": "be9ee02",
			"subject": "[TASK] Order use statements alphabetically"
		},
		{
			"sha1": "ee4ab3c",
			"subject": "[TASK] Replace assertion method to use dedicated method"
		}
	]
}
```

[`/git/status/review.local.typo3.org/typo3_src/2/full`](http://local.typo3.org/git/status/review.local.typo3.org/typo3_src/2/full)
Will return the last 2 commit messages in full format.

```
{
	"status": "OK",
	"stdout": [
		{
			"commit": "848b2fc059c6414eef3d9305c8ecd0821dce831b",
			"abbreviated_commit": "848b2fc",
			"tree": "859dc9ee1b34a7ac47495f2c48b750d7f984ebdd",
			"abbreviated_tree": "859dc9e",
			"parent": "be9ee02f0e4bb826a81dc4729d5f6b6ce9f875c2",
			"abbreviated_parent": "be9ee02",
			"subject": "[BUGFIX] RTE fields do not handle eval validation like required",
			"body": "Resolves: #70246\nReleases: master\nChange-Id: I3e536ab85740b58e07f1b262692bf3a9773edd62\nReviewed-on: https://review.typo3.org/44810\nReviewed-by: Andreas Fernandez <typo3@scripting-base.de>\nTested-by: Andreas Fernandez <typo3@scripting-base.de>\nReviewed-by: Christian Kuhn <lolli@schwarzbu.ch>\nTested-by: Christian Kuhn <lolli@schwarzbu.ch>\n",
			"author": {
				"name": "Frank Nägler",
				"email": "frank.naegler@typo3.org",
				"date": "Fri, 20 Nov 2015 10:51:59 +0100"
			},
			"commiter": {
				"name": "Christian Kuhn",
				"email": "lolli@schwarzbu.ch",
				"date": "Fri, 20 Nov 2015 15:08:30 +0100"
			}
		},
		{
			"commit": "be9ee02f0e4bb826a81dc4729d5f6b6ce9f875c2",
			"abbreviated_commit": "be9ee02",
			"tree": "fb94900a4491682b2dbcb24c19bc319a8709672c",
			"abbreviated_tree": "fb94900",
			"parent": "ee4ab3cf1901d4a86f7fc107a24d80d45d1d1a63",
			"abbreviated_parent": "ee4ab3c",
			"subject": "[TASK] Order use statements alphabetically",
			"body": "Resolves: #71726\nReleases: master\nChange-Id: I4a356c8da668acee555149eee9cf56ccdb4dc0ee\nReviewed-on: https://review.typo3.org/44822\nReviewed-by: Andreas Fernandez <typo3@scripting-base.de>\nTested-by: Andreas Fernandez <typo3@scripting-base.de>\nReviewed-by: Christian Kuhn <lolli@schwarzbu.ch>\nTested-by: Christian Kuhn <lolli@schwarzbu.ch>\n",
			"author": {
				"name": "Wouter Wolters",
				"email": "typo3@wouterwolters.nl",
				"date": "Fri, 20 Nov 2015 14:02:23 +0100"
			},
			"commiter": {
				"name": "Christian Kuhn",
				"email": "lolli@schwarzbu.ch",
				"date": "Fri, 20 Nov 2015 14:58:22 +0100"
			}
		}
	]
}
```

### Pull
[`/git/pull/review.local.typo3.org/typo3_src`](http://local.typo3.org/git/pull/review.local.typo3.org/typo3_src)

Will return the result of the pull command (defaults to origin master).
```
{
  "status": "OK",
  "stdout": [
    "Already up-to-date."
  ],
  "stderr": [
    "warning: expected SRV RR, found RR type 1",
    "From git://github.com/TYPO3/TYPO3.CMS",
    " * branch            master     -> FETCH_HEAD"
  ]
}
```

You may specify remote and branch parameters:
[`/git/pull/review.local.typo3.org/typo3_src/{remote}/{branch}`](http://local.typo3.org/git/pull/review.local.typo3.org/typo3_src/{remote}/{branch})

### Tag
[`/git/tag/review.local.typo3.org/typo3_src`](http://local.typo3.org/git/tag/review.local.typo3.org/typo3_src)

Will return the list of available tags.
```
{
  "status": "OK",
  "stdout": [
    "6.2.0",
    "6.2.1",
    "6.2.10",
    "6.2.10-rc1",
    "6.2.11",
    "6.2.12",
    "6.2.13",
    "6.2.14",
    "6.2.15",
    . . .
  ]
}
```

## Version controller
[`/version`](http://local.typo3.org/version)

Will return the current TYPO3 manager version.

```
{
  "status": "OK",
  "stdout": "1.0.0"
}
```

## TODO

* Add phpunit routes