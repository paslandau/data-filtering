# DEPRECATED ⛔ 

This repository has been deprecated as of 2019-01-27. That code was written a long time ago and has been unmaintained for several years.
Thus, repository will now be [archived](https://github.blog/2017-11-08-archiving-repositories/).
If you are interested in taking over ownership, feel free to [contact me](https://www.pascallandau.com/about/).

---


# data-filtering
[![Build Status](https://travis-ci.org/paslandau/data-filtering.svg?branch=master)](https://travis-ci.org/paslandau/data-filtering)

Framework to filter (extract,transform,evaluate) data based on rules

## Description
[todo]

## Requirements

- PHP >= 5.5

## Installation

The recommended way to install data-filtering is through [Composer](http://getcomposer.org/).

    curl -sS https://getcomposer.org/installer | php

Next, update your project's composer.json file to include data-filtering:

    {
        "repositories": [ { "type": "composer", "url": "http://packages.myseosolution.de/"} ],
        "minimum-stability": "dev",
        "require": {
             "paslandau/data-filtering": "dev-master"
        }
        "config": {
            "secure-http": false
        }
    }

_**Caution:** You need to explicitly set `"secure-http": false` in order to access http://packages.myseosolution.de/ as repository. 
This change is required because composer changed the default setting for `secure-http` to true at [the end of february 2016](https://github.com/composer/composer/commit/cb59cf0c85e5b4a4a4d5c6e00f827ac830b54c70#diff-c26d84d5bc3eed1fec6a015a8fc0e0a7L55)._


After installing, you need to require Composer's autoloader:
```php

require 'vendor/autoload.php';
```
