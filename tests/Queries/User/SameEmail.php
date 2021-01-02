<?php

namespace Tests\Queries\User;

use Imunew\Laravel\Database\Queries\Query;
use RuntimeException;
use Tests\Models\User;

/**
 * Class SameEmail
 * @package Tests\Queries\User
 *
 * @mixin User
 */
class SameEmail extends Query
{
    /**
     * SameEmail constructor.
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
        if (!array_key_exists('email', $parameters)) {
            throw new RuntimeException('The parameter \'email\' must not be empty.');
        }
        $this->whereEmail($parameters['email']);
        return $this;
    }
}
