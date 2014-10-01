# PHP Server Behat Extension

Extension to run PHP's built-in web server during tests

## Installation

Require package `adamquaile/behat-php-server-extension` via composer:

    composer require --dev "adamquaile/behat-php-server-extension"

Add configuration to your `behat.yml` (the `host` and `router` keys are optional; the default host will be `localhost:8000`):

    default:
        extensions:
            AdamQuaile\Behat\PhpServerExtension:
                host: 0.0.0.0:8080
                docroot: ./web
                router: app/router_test.php
