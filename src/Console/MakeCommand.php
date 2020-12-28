<?php

namespace Imunew\Laravel\Database\Queries\Console;

use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class MakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:database-query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new database query class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'DatabaseQuery';

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $model = $this->option('model');
        if (empty($model)) {
            throw new InvalidArgumentException('The model must not be empty.');
        }

        return parent::handle();
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/database-query.stub';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Database\Queries';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'Generate a database query for the given model.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function replaceNamespace(&$stub, $name)
    {
        parent::replaceNamespace($stub, $name);

        $model = $this->replaceModel($this->option('model'));

        $stub = str_replace(
            'DummyModelNamespace',
            $model,
            $stub
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function replaceClass($stub, $name)
    {
        $replaced = parent::replaceClass($stub, $name);

        $model = $this->replaceModel($this->option('model'));

        return str_replace(
            'DummyModelClass',
            class_basename($model),
            $replaced
        );
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param string $model
     * @return string
     */
    private function replaceModel(string $model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }
        $rootNamespace = $this->rootNamespace();
        $modelNamespace = null;
        $model = str_replace('/', '\\', $model);
        if (class_exists($model)) {
            $modelNamespace = $model;
        } elseif (class_exists($rootNamespace. '\\'. $model)) {
            $modelNamespace = $rootNamespace. '\\'. $model;
        } elseif (class_exists($rootNamespace. '\\Models\\'. $model)) {
            $modelNamespace = $rootNamespace. '\\Models\\'. $model;
        }
        if (empty($modelNamespace)) {
            throw new InvalidArgumentException("The Model ('{$model}') class does not exists.");
        }
        return $modelNamespace;
    }
}
