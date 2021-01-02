<?php

namespace Tests\App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package Tests\App
 *
 * @property string $name
 * @property string $email
 *
 * @method User whereName(string $name)
 * @method User whereEmail(string $email)
 */
class User extends Model
{
}
