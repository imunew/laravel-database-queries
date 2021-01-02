<?php

namespace Tests\Console;

use Imunew\Laravel\Database\Queries\Query;
use Tests\TestCase;

class MakeCommandTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        require_once app_path('/User.php');
        require_once app_path('/Models/Team.php');
        require_once app_path('/Models/TeamUser.php');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        foreach (glob($this->getBasePath(). '/app/Database/Queries/{*,*/*}.php', GLOB_BRACE) as $filename) {
            unlink($filename);
        }
        foreach (glob($this->getBasePath(). '/app/Database/Queries/*', GLOB_ONLYDIR) as $directory) {
            rmdir($directory);
        }
    }

    /**
     * @test
     * @dataProvider getTestData
     * @param string $name
     * @param string $model
     */
    public function handle(string $name, string $model)
    {
        $this->artisan("make:database-query {$name} --model={$model}")
            ->assertExitCode(0)
            ->run()
        ;
        $filePath = $this->getBasePath(). "/app/Database/Queries/{$name}.php";
        $this->assertFileExists($filePath);

        require_once $filePath;
        $namespace = str_replace('/', '\\', $name);
        $rootNamespace = $this->app->getNamespace();
        $this->assertTrue(class_exists($rootNamespace. "Database\\Queries\\{$namespace}"));
        $this->assertTrue(is_subclass_of($rootNamespace. "Database\\Queries\\{$namespace}", Query::class));
    }

    /**
     * @return array
     */
    public function getTestData()
    {
        return [
            ['User/SameEmail', 'User'],
            ['User/SameName', 'Tests/App/User'],
            ['Team/SameName', 'Team']
        ];
    }

    /**
     * @test
     * @dataProvider getInvalidTestData
     * @param string $name
     * @param string $model
     */
    public function handle_fail(string $name, string $model)
    {
        $this->artisan("make:database-query {$name} --model={$model}")
            ->assertExitCode(1)
            ->run()
        ;
    }

    /**
     * @return array
     */
    public function getInvalidTestData() : array
    {
        return [
            ['User/SameEmail', 'Missing/User'],
            ['User/SameEmail', 'inv@lid-character'],
            ['User/SameEmail', ''],
        ];
    }
}
