<?php

namespace Tests\Queries\User;

use BadMethodCallException;
use InvalidArgumentException;
use Tests\Models\User as UserModel;
use Tests\Queries\Team\SameName as SameTeamName;
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
        $sameName = new SameName(['name' => $this->user->name], ['teams']);
        $user = $sameName->first();
        $this->assertInstanceOf(UserModel::class, $user);
        assert($user instanceof UserModel);
        $this->assertSame($this->user->name, $user->name);
    }

    /**
     * @test
     */
    public function build_fail_by_invalid_query()
    {
        $sameEmail = new SameEmail(['email' => $this->user->email]);
        $this->expectException(InvalidArgumentException::class);
        $sameEmail->build(new SameTeamName(['name' => 'The team']));
    }

    /**
     * @test
     */
    public function construct_fail_by_invalid_parameters()
    {
        $this->expectException(InvalidArgumentException::class);
        new SameEmail([]);
    }

    /**
     * @test
     */
    public function fail_by_call_write_method()
    {
        $sameName = new SameName(['name' => $this->user->name]);
        $this->expectException(BadMethodCallException::class);
        $sameName->save();
    }
}
