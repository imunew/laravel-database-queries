<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class User
 * @package Tests\Models
 *
 * @property string $name
 * @property string $email
 *
 * @property-read Team[]|Collection $teams
 *
 * @method User whereName(string $name)
 * @method User whereEmail(string $email)
 */
class User extends Model
{
    /**
     * @return HasManyThrough
     */
    public function teams()
    {
        return $this->hasManyThrough(Team::class, TeamUser::class);
    }
}
