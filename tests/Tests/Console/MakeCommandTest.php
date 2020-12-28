<?php

namespace Tests\Console;

use Imunew\Laravel\Database\Queries\Query;
use Tests\TestCase;

class MakeCommandTest extends TestCase
{
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
        $this->assertTrue(class_exists("App\\Database\\Queries\\{$namespace}"));
        $this->assertTrue(is_subclass_of("App\\Database\\Queries\\{$namespace}", Query::class));
    }

    /**
     * @return array
     */
    public function getTestData()
    {
        return [
            ['User/SameEmail', 'Tests/Models/User'],
            ['User/SameName', 'Tests/Models/User']
        ];
    }
}
