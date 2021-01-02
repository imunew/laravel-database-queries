<?php

namespace Tests\Queries\Team;

use Imunew\Laravel\Database\Queries\Query;
use RuntimeException;
use Tests\Models\Team;

/**
 * Class SameName
 * @package Tests\Queries\Team
 *
 * @mixin Team
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
        parent::__construct(Team::class, $parameters, $with);
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
