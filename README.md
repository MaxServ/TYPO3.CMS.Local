# TYPO3.CMS.Local
Manage locally installed TYPO3 sites. This is a site to help manage locally installed TYPO3 sites in review and development boxes built by [TYPO3 Packer](https://github.com/Tuurlijk/TYPO3.Packer).

## Installation
Clone this repository and then do a:
`composer install`

## Working requests

1) `/site/list`

This will return a json object containing the TYPO3 sites found in `/var/www`.
```
[
"6.2.local.typo3.org",
"7.6.local.typo3.org",
"dev-master.local.typo3.org",
"review.local.typo3.org"
]
```

2) `/git/list/review.local.typo3.org

This will return a json object containing all the git repositories found in the given site.

```
[
"typo3_src/.git",
"typo3conf/ext/accountmanagement/.git",
"typo3conf/ext/distributionmanagement/.git",
"typo3conf/ext/icon_api/.git",
"typo3conf/ext/typo3_console/.git",
"vendor/cogpowered/finediff/.git",
"vendor/doctrine/instantiator/.git",
"vendor/mikey179/vfsStream/.git",
"vendor/pear/http_request2/.git",
"vendor/pear/net_url2/.git",
"vendor/pear/pear_exception/.git",
"vendor/phpdocumentor/reflection-docblock/.git",
"vendor/phpspec/prophecy/.git",
"vendor/phpunit/php-code-coverage/.git",
"vendor/phpunit/php-file-iterator/.git",
"vendor/phpunit/php-text-template/.git",
"vendor/phpunit/php-timer/.git",
"vendor/phpunit/php-token-stream/.git",
"vendor/phpunit/phpunit-mock-objects/.git",
"vendor/phpunit/phpunit/.git",
"vendor/phpwhois/idna-convert/.git",
"vendor/psr/http-message/.git",
"vendor/psr/log/.git",
"vendor/sebastian/comparator/.git",
"vendor/sebastian/diff/.git",
"vendor/sebastian/environment/.git",
"vendor/sebastian/exporter/.git",
"vendor/sebastian/global-state/.git",
"vendor/sebastian/recursion-context/.git",
"vendor/sebastian/version/.git",
"vendor/swiftmailer/swiftmailer/.git",
"vendor/symfony/console/.git",
"vendor/symfony/finder/.git",
"vendor/symfony/yaml/.git",
"vendor/typo3/class-alias-loader/.git",
"vendor/typo3/cms-composer-installers/.git"
]
```
