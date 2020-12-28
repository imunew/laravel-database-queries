<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package Tests\Models
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
