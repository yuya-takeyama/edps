# EDPS

EDPS is a PHP 5.2 port of Événement.

[![Build Status](https://secure.travis-ci.org/yuya-takeyama/edps.png)](http://travis-ci.org/yuya-takeyama/edps)

## Fetch

The recommended way to install EDPS is [through composer](http://getcomposer.org).

Just create a composer.json file for your project:

```JSON
{
    "require": {
        "edps/edps": "dev-master"
    }
}
```

And run these two commands to install it:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install

Now you can add the autoloader, and you will have access to the library:

```php
<?php
require 'vendor/autoload.php';
```

## Usage

### Creating an Emitter

```php
<?php
$emitter = new Edps_EventEmitter();
```

### Adding Listeners

```php
<?php
$emitter->on('user.create', function (User $user) use ($logger) {
    $logger->log(sprintf("User '%s' was created.", $user->getLogin()));
});
```

### Emitting Events

```php
<?php
$emitter->emit('user.create', array($user));
```

Tests
-----

    $ phpunit

License
-------
MIT, see LICENSE.
