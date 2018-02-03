# wait4services

[![build status](https://travis-ci.org/uuf6429/wait4services.svg?branch=master)](https://travis-ci.org/uuf6429/wait4services)
[![coverage report](https://codecov.io/gh/uuf6429/wait4services/branch/master/graph/badge.svg)](https://codecov.io/gh/uuf6429/wait4services/branch/master/)
[![Minimum PHP Version](https://img.shields.io/packagist/php-v/uuf6429/wait4services.svg)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/uuf6429/wait4services/blob/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/uuf6429/wait4services.svg)](https://packagist.org/packages/uuf6429/wait4services)
[![Dependency Status](https://beta.gemnasium.com/badges/github.com/uuf6429/wait4services.svg)](https://beta.gemnasium.com/projects/github.com/uuf6429/wait4services)

A simple tool for waiting until all required services are up and running.

![screenshot](https://i.imgur.com/G8AQk02.png)

## Installation

Add via composer, either locally or globally (depending on your needs):
```bash
composer require uuf6429/wait4services
```
_(to install globally just add "global" before "require")_

## Usage

Let's say you want to run some tests on a system bootstrapped from Docker, this tool could be used like so:
```bash
# start docker services
docker-compose up -d

# wait for the services
vendor/bin/wait4services \
  "pdo-mysql://dbuser:dbpass@docker/somedb" \
  "http://docker/wp-admin"

# run your tests
vendor/bin/phpunit
```

## Extending

It's a bit difficult to extend due to autoloading, however it can be achieved with the following steps:

1.  Create your handler extending `uuf6429\WFS\Handler\Handler`
2.  At the end of that file (or in some other file) use the following code:
    
    ```php
    if (uuf6429\WFS\HandlerManager::class) {
        uuf6429\WFS\HandlerManager::getInstance()
            ->register(new YourCustomHandler());
    }
    ```
    
3.  In your `composer.json` make sure to put the previous file in `autoload\files` section:
    
    ```json
    "autoload": {
        "files": ["path/to/your/handler.php"]
    }
    ```
