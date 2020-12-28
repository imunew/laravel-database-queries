<?php

namespace Imunew\Laravel\Database\Queries\Contracts;

interface Query
{
    /**
     * @param Query|null $query
     * @return static
     */
    public function build(?Query $query = null);
}
