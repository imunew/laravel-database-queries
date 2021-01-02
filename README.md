# laravel-database-queries
[![CircleCI](https://circleci.com/gh/imunew/laravel-database-queries.svg?style=svg)](https://circleci.com/gh/imunew/laravel-database-queries)  
This package provides abstract Database Query class, Chain class, `make:database-query` command.

## abstract Database Query class
The abstract `Database Query` class has the following features.

- Delegate query function to Eloquent Model (Query Builder)
- Declare (limited) which Eloquent Model to use
- Set query parameters in the constructor

```php
namespace App\Database\Queries\User;

use App\Models\User;
use Imunew\Laravel\Database\Queries\Query;
use RuntimeException;

/**
 * Class SameName
 * @package App\Database\Queries\User
 *
 * @mixin User
 */
class SameName extends Query
{
    /**
     * SameName constructor.
     * @param array $parameters
     * @param array $with
     */
    public function __construct(array $parameters, array $with = [])
    {
        parent::__construct(User::class, $parameters, $with);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildQuery(array $parameters)
    {
        if (!array_key_exists('name', $parameters)) {
            throw new RuntimeException('The parameter \'name\' must not be empty.');
        }
        $this->whereName($parameters['name']);
        return $this;
    }
}
```

```php
use App\Database\Queries\User\SameName;

function findByName(string $name) {
    $query = new SameName(['name' => $name]);
    return $query->build()->get();
}
```

## Chain class
The Chain class has the following features.

- You can combine multiple Query classes (specify the same model class) and build at once

```php
use App\Database\Queries\User\SameName;
use App\Database\Queries\User\SameEmail;
use Imunew\Laravel\Database\Queries\Chain;

function firstByNameAndEmail(string $name, string $email) {
    $chain = Chain::all([
        new SameName(['name' => $name]), 
        new SameEmail(['email' => $email])
    ]);
    return $chain->build()->first();
}
```

## `make:database-query` command
You can create a Query by executing the command as follows.

```bash
$ php artisan make:database-query {name} --model={model}
```
