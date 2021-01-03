<?php

namespace Imunew\Laravel\Database\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\ForwardsCalls;
use InvalidArgumentException;

/**
 * Class Query
 * @package Imunew\Laravel\Database\Queries
 *
 * @method int count(string $columns = '*')
 * @method Model find(mixed $id, array $columns = ['*'])
 * @method Model findOrFail(mixed $id, array $columns = ['*'])
 * @method Model first(array|string $columns = ['*'])
 * @method Model firstOrFail(array|string $columns = ['*'])
 * @method string toSql()
 * @method Model[]|Collection get(array|string $columns = ['*'])
 */
abstract class Query implements Contracts\Query
{
    use ForwardsCalls;

    /** @var string */
    private $modelClass;

    /** @var Model|Builder */
    private $model;

    /** @var array */
    private $parameters;

    /** @var array */
    private $with;

    /**
     * Query constructor.
     * @param string $modelClass
     * @param array $parameters
     * @param array $with
     */
    public function __construct(string $modelClass, array $parameters, array $with = [])
    {
        if (!is_subclass_of($modelClass, Model::class)) {
            throw new InvalidArgumentException("{$modelClass} is not instance of Model.");
        }
        if (!$this->validateParameters($parameters, $errorMessage)) {
            throw new InvalidArgumentException($errorMessage);
        }
        $this->modelClass = $modelClass;
        $this->parameters = $parameters;
        $this->with = $with;
    }

    /**
     * @param Contracts\Query|null $query
     * @return static
     */
    public function build(?Contracts\Query $query = null)
    {
        $modelClass = $this->modelClass;
        if (empty($query)) {
            $this->model = new $modelClass();
        } else {
            assert($query instanceof Query);
            if ($query->modelClass !== $modelClass) {
                throw new InvalidArgumentException("The query must be {$modelClass} instance.");
            }
            $this->model = $query->model;
        }
        if (!empty($this->with)) {
            $this->model->with($this->with);
        }
        return $this->buildQuery($this->parameters);
    }

    /**
     * @param array $parameters
     * @param string|null $errorMessage
     * @return bool
     */
    abstract protected function validateParameters(array $parameters, ?string &$errorMessage);

    /**
     * @param array $parameters
     * @return static
     */
    abstract protected function buildQuery(array $parameters);

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $result = $this->forwardCallTo($this->model, $name, $arguments);
        if (($result instanceof $this->model) || ($result instanceof Builder)) {
            $this->model = $result;
        }
        return $result;
    }
}
