<?php

namespace Tests\Queries\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Imunew\Laravel\Database\Queries\Chain;
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

        // An unique user should be found by email.
        $sameEmail = new SameEmail(['email' => $this->user->email]);

        // An unique user should be found by name and email.
        $chain = Chain::all([
            $sameName,
            $sameEmail
        ]);
        assert($chain instanceof SameEmail);
        $this->assertCount(1, $chain->get());
    }
}
