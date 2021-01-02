<?php

namespace Tests\Queries\User;

use RuntimeException;
use Tests\TestCase;

class InvalidQueryTest extends TestCase
{
    /**
     * @test
     */
    public function invalid_model_class()
    {
        $this->expectException(RuntimeException::class);
        new InvalidQuery([]);
    }
}
