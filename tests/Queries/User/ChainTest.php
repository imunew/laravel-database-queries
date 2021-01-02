<?php

namespace Tests\Queries\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Imunew\Laravel\Database\Queries\Chain;
use Imunew\Laravel\Database\Queries\Query;
use Tests\Models\User as UserModel;
use Tests\TestCase;

class ChainTest extends TestCase
{
    use WithFaker;

    /** @var UserModel[]|Collection */
    private $users;

    /** @var UserModel */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        // All users have the same name.
        $this->users = factory(UserModel::class, 3)->create([
            'name' => $this->faker->name
        ]);
        $this->user = $this->users->shuffle()->first();
    }

    /**
     * @test
     */
    public function build()
    {
        $sameName = new SameName(['name' => $this->user->name]);
        // All users have the same name.
        $this->assertCount($this->users->count(), $sameName->build()->get());

        // An unique user should be found by email.
        $sameEmail = new SameEmail(['email' => $this->user->email]);
        $this->assertCount(1, $sameEmail->build()->get());

        // An unique user should be found by name and email.
        $chain = Chain::all([
            $sameName,
            $sameEmail
        ]);
        assert($chain instanceof Query);
        $this->assertCount(1, $chain->build()->get());
    }
}
