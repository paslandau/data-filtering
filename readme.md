#DataFiltering
[![Build Status](https://travis-ci.org/paslandau/DataFiltering.svg?branch=master)](https://travis-ci.org/paslandau/DataFiltering)

Framework to filter (extract,transform,evaluate) data based on rules

##Description
[todo]

##Requirements

- PHP >= 5.5

##Installation

The recommended way to install DataFiltering is through [Composer](http://getcomposer.org/).

    curl -sS https://getcomposer.org/installer | php

Next, update your project's composer.json file to include DataFiltering:

    {
        "repositories": [
            {
                "type": "git",
                "url": "https://github.com/paslandau/DataFiltering.git"
            }
        ],
        "require": {
             "paslandau/DataFiltering": "~0"
        }
    }

After installing, you need to require Composer's autoloader:
```php

require 'vendor/autoload.php';
```