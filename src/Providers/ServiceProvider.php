<?php

namespace Imunew\Laravel\Database\Queries\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Imunew\Laravel\Database\Queries\Console\MakeCommand;

/**
 * Class ServiceProvider
 * @package Imunew\Laravel\Database\Queries\Providers
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->commands([
            MakeCommand::class
        ]);
    }
}
