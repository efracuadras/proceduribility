## Laravel Proceduribility

Package for a fluent and elegant way to use stored procedures.

**This project is still on development mode, don't use it on production environment**

## Installation

Require this package with composer:

```
composer require mathiasd88/proceduribility
```

After updating composer, add the ServiceProvider to the providers array in config/app.php

### Laravel 5.x:

```
Mathiasd88\Proceduribility\ProceduribilityServiceProvider::class,
```

If you want to use the facade to log messages, add this to your facades in app.php:

```
'Procedure' => Mathiasd88\Proceduribility\StoredProcedure::class,
```

## Usage

Example:

```php
$procedure = Procedure::name('sp_create_post')
    ->paramsIn([
        'title' => 'Super title',
        'body'  => 'Dummy text'
    ])->paramsOut(['message' => PDO::PARAM_STR, 'errors' => PDO::PARAM_INT]) // output values
    ->run(); // or execute()

echo $procedure->output(); // ['message' => 'Post created', 'errors' => 0]
```


