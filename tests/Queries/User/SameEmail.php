<?php

namespace Tests\Queries\User;

use Imunew\Laravel\Database\Queries\Query;
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
    protected function validateParameters(array $parameters, ?string &$errorMessage)
    {
        if (!array_key_exists('email', $parameters)) {
            $errorMessage = 'The parameter \'email\' must not be empty.';
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildQuery(array $parameters)
    {
        $this->whereEmail($parameters['email']);
        return $this;
    }
}
