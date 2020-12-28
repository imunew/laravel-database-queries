<?php

namespace Tests\Queries;

use Tests\Models\User as UserModel;
use Tests\TestCase;

class SameNameTest extends TestCase
{
    /** @var UserModel */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $users = factory(UserModel::class, 3)->create();
        $this->user = $users->shuffle()->first();
    }

    /**
     * @test
     */
    public function build()
    {
        $sameName = new SameName(['name' => $this->user->name]);
        $user = $sameName->build()->first();
        $this->assertInstanceOf(UserModel::class, $user);
        assert($user instanceof UserModel);
        $this->assertSame($this->user->name, $user->name);
    }
}
