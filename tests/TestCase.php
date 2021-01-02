<?php

namespace Tests;

use Imunew\Laravel\Database\Queries\Providers\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class TestCase
 * @package Tests
 */
class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->withFactories(__DIR__. '/../database/factories');
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * {@inheritdoc}
     */
    protected function getBasePath()
    {
        return realpath(__DIR__. '/../laravel');
    }
}
