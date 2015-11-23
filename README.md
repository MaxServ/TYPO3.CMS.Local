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