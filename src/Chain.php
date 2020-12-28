<?php

namespace Imunew\Laravel\Database\Queries;

use Imunew\Laravel\Database\Queries\Contracts\Query as QueryContract;

/**
 * Class Chain
 * @package Imunew\Laravel\Database\Queries
 */
final class Chain
{
    /**
     * @param QueryContract[]|array $queries
     * @return QueryContract
     */
    public static function all(array $queries)
    {
        $lastQuery = null;
        foreach ($queries as $query) {
            /* @var QueryContract $query */
            $lastQuery = $query->build($lastQuery);
        }
        return $lastQuery;
    }
}
