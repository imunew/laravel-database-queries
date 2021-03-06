<?php

namespace Tests\Queries\User;

use Tests\Models\User as UserModel;
use Tests\TestCase;

class SameEmailTest extends TestCase
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
        $sameEmail = new SameEmail(['email' => $this->user->email]);
        $user = $sameEmail->first();
        $this->assertInstanceOf(UserModel::class, $user);
        assert($user instanceof UserModel);
        $this->assertSame($this->user->email, $user->email);
    }
}
