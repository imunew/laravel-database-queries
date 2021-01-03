<?php

namespace Tests\Queries\User;

use InvalidArgumentException;
use Tests\TestCase;

class InvalidQueryTest extends TestCase
{
    /**
     * @test
     */
    public function invalid_model_class()
    {
        $this->expectException(InvalidArgumentException::class);
        new InvalidQuery([]);
    }
}
