#Variables

This is a package for the Laravel framework, it allows for a specified set of variables to be overridden in the database by the user of the site

## Installation

Using Composer, edit your `composer.json` file to require `devfactory/media`.

    "require": {
      "devfactory/variables": "1.0.*"
    }

Then from the terminal run

    composer update

Then in your `app/config/app.php` file register the service provider:

    'Devfactory\Variables\VariablesServiceProvider'

and the Facade:

    'Variables'       => 'Devfactory\Variables\Facades\VariablesFacade',

Run the migration to create the DB table:

    php artisan migrate --package=devfactory/variables

Finally, publish the config to make changes to where and how the files are stored:

    php artisan config:publish devfactory/variables

You have to add the Controller to your routes.php, so that you can set your own url/filters.

    Route::group(array('before' => 'admin-auth'), function() {
        Route::controller('variables', 'Devfactory\Variables\Controllers\VariablesController');
    });

## Usage

Visiting the url you set in your routes file as shown above, allows you to see all the current variables, as well as override them by entering a new value and saving.

You can then use the variables through calls to the `Variables` facade:

```php
<?php

$api_key = Variables::get('api_key'); // da46f8af58aec448c784dd421660f7635d404feb
```

Other public methods in the Facade are:

```php
<?php

// Retrieve an array of all the variables
Variables::getAll();

// Set the value of a variable
Variables::set('api_key', 'da46f8af58aec448c784dd421660f7635d404feb');

// Unset the value of a variable stored in the DB
Variables::remove('api_key');
```
