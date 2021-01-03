<?php

namespace Tests\Queries\User;

use DateTime;
use Imunew\Laravel\Database\Queries\Query;

/**
 * Class InvalidQuery
 * @package Tests\Queries\User
 */
class InvalidQuery extends Query
{
    /**
     * SameEmail constructor.
     * @param array $parameters
     * @param array $with
     */
    public function __construct(array $parameters, array $with = [])
    {
        parent::__construct(DateTime::class, $parameters, $with);
    }

    /**
     * {@inheritdoc}
     */
    protected function validateParameters(array $parameters, ?string &$errorMessage)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildQuery(array $parameters)
    {
        return $this;
    }
}
