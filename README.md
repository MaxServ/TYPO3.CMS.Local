# TYPO3.CMS.Local
Manage locally installed TYPO3 sites. This is a site to help manage locally installed TYPO3 sites in review and development boxes built by [TYPO3 Packer](https://github.com/Tuurlijk/TYPO3.Packer).

This can replace the existing default page on [http://local.typo3.org](http://local.typo3.org) and can be used by third party code (like Chrome extensions) to get information about running sites, exising git repositories etc.

## Installation
Clone this repository and then do a:
`composer install`

## Working requests

### Site controller

#### List
`/site/list`

Will return a json object containing the TYPO3 sites found in `/var/www`.
```
{
	"status": "OK",
	"data": [
		"6.2.local.typo3.org",
		"7.6.local.typo3.org",
		"dev-master.local.typo3.org",
		"review.local.typo3.org"
	]
}
```

### Git repository controller

### Branch
`/git/branch/review.local.typo3.org/typo3_src`

Will return the list of available remote branches.
```
{
  "status": "OK",
  "data": [
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
		...
  ]
}
```

#### List
`/git/list/review.local.typo3.org`

Will return a json object containing all the git repositories found in the given site.

```
{
	"status": "OK",
	"data": [
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

### Status
`/git/status/review.local.typo3.org/vendor!mikey179!vfsStream`

Will return a json object containing the latest commit sha1 and message. Note that the slashes in the repository are encoded as exclamation marks in the request. You can either use '!' or '%21' to encode the '/' characters.
```
{
	"status": "OK",
	"data": "73bcb60 update to latest travis changes\n"
}
```

`/git/status/review.local.typo3.org/typo3_src/3`
Will return the last 3 commit messages in oneline format.
```
{
	"status": "OK",
	"data": [
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

`/git/status/review.local.typo3.org/typo3_src/2/full`
Will return the last 2 commit messages in full format.

```
{
	"status": "OK",
	"data": [
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
`/git/pull/review.local.typo3.org/typo3_src`

Will return the result of the pull command (defaults to origin master).
```
{
  "status": "OK",
  "data": [
    "Already up-to-date.",
    "From https://git.typo3.org/Packages/TYPO3.CMS",
    " * branch            master     -> FETCH_HEAD",
  ]
}
```

You may specify remote and branch parameters:
`/git/pull/review.local.typo3.org/typo3_src/{remote}/{branch}`

### Tag
`/git/tag/review.local.typo3.org/typo3_src`

Will return the list of available tags.
```
{
  "status": "OK",
  "data": [
		"6.2.0",
		"6.2.1",
		"6.2.10",
		"6.2.10-rc1",
		"6.2.11",
		"6.2.12",
		"6.2.13",
		"6.2.14",
		"6.2.15",
		...
  ]
}
```

## Version controller
`/version`

Will return the current TYPO3 manager version.

```
{
"status": "OK",
"data": "1.0.0"
}
```

## TODO

* Add more routes
* Write more documentation