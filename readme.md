#data-filtering
[![Build Status](https://travis-ci.org/paslandau/data-filtering.svg?branch=master)](https://travis-ci.org/paslandau/data-filtering)

Framework to filter (extract,transform,evaluate) data based on rules

##Description
[todo]

##Requirements

- PHP >= 5.5

##Installation

The recommended way to install data-filtering is through [Composer](http://getcomposer.org/).

    curl -sS https://getcomposer.org/installer | php

Next, update your project's composer.json file to include DataFiltering:

    {
        "repositories": [
            {
                "type": "git",
                "url": "https://github.com/paslandau/data-filtering.git"
            }
        ],
        "require": {
             "paslandau/data-filtering": "~0"
        }
    }

After installing, you need to require Composer's autoloader:
```php

require 'vendor/autoload.php';
```