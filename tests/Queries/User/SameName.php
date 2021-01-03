<?php

namespace Tests\Queries\User;

use Imunew\Laravel\Database\Queries\Query;
use Tests\Models\User;

/**
 * Class SameName
 * @package Tests\Queries\User
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
    protected function validateParameters(array $parameters, ?string &$errorMessage)
    {
        if (!array_key_exists('name', $parameters)) {
            $errorMessage = 'The parameter \'name\' must not be empty.';
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildQuery(array $parameters)
    {
        $this->whereName($parameters['name']);
        return $this;
    }
}
