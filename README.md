## Laravel Proceduribility

Package for a fluent and elegant way to use stored procedures.

**This project is still on development mode, don't use it on production environment.**

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

If you want to use the facade, add this to your facades in app.php:

```
'Procedure' => Mathiasd88\Proceduribility\Facades\Procedure::class,
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

return $procedure->output(); // ['message' => 'Post created', 'errors' => 0]

return $procedure->output('message'); // 'Post created'
```

Instead of

```php
$stmt = \DB::getPdo()->prepare("begin sp_create_post(
    :title,
    :body,
    :message,
    :errors
    ); end;"
);

$stmt->bindParam('title', $var1);
$stmt->bindParam('body', $var2);
$stmt->bindParam('message', $message, PDO::PARAM_STR, 3000);
$stmt->bindParam('errors', $errors, PDO::PARAM_INT);
$stmt->execute();

return $message; // 'Post created'

return $errors; // 0
```
