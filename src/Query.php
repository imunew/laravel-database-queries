<?php

namespace Imunew\Laravel\Database\Queries;

use BadMethodCallException;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Traits\ForwardsCalls;
use InvalidArgumentException;
use RuntimeException;

/**
 * Class Query
 * @package Imunew\Laravel\Database\Queries
 *
 * @method mixed average(string $column)
 * @method mixed avg(string $column)
 * @method int count(string $columns = '*')
 * @method bool doesntExist()
 * @method bool exists()
 * @method static find(mixed $id, array $columns = ['*'])
 * @method static findMany(Arrayable|array $ids, $columns = ['*'])
 * @method static findOrFail(mixed $id, array $columns = ['*'])
 * @method static first(array|string $columns = ['*'])
 * @method static firstOr(array|string $columns = ['*'], Closure $callback = null)
 * @method static firstOrFail(array|string $columns = ['*'])
 * @method static firstWhere(Closure|string|array|Expression $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static[]|Collection get(array|string $columns = ['*'])
 * @method static[]|Collection getModels(array|string $columns = ['*'])
 * @method mixed max(string $column)
 * @method mixed min(string $column)
 * @method LengthAwarePaginator paginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
 * @method LengthAwarePaginator simplePaginate(int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
 * @method mixed sum(string $column)
 * @method string toSql()
 */
abstract class Query implements Contracts\Query
{
    use ForwardsCalls;

    /** @var array */
    private const WRITE_METHODS = [
        'create',
        'decrement',
        'delete',
        'finishSave',
        'firstOrCreate',
        'firstOrNew',
        'forceCreate',
        'forceDelete',
        'increment',
        'insert',
        'performDeleteOnModel',
        'performUpdate',
        'push',
        'restore',
        'save',
        'saveOrFail',
        'saveQuietly',
        'touch',
        'truncate',
        'update',
        'updateOrCreate',
    ];

    /** @var array */
    private const READ_METHODS = [
        'average',
        'avg',
        'count',
        'doesntExist',
        'exists',
        'find',
        'findMany',
        'findOrFail',
        'first',
        'firstOr',
        'firstOrFail',
        'firstWhere',
        'get',
        'getModels',
        'max',
        'min',
        'paginate',
        'simplePaginate',
        'sum',
    ];

    /** @var string */
    private $modelClass;

    /** @var Model|Builder */
    private $model;

    /** @var array */
    private $parameters;

    /** @var array */
    private $with;

    /** @var bool */
    private $isBuilt = false;

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
        if ($this->isBuilt) {
            return $this;
        }
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
        return tap($this->buildQuery($this->parameters), function () {
            $this->isBuilt = true;
        });
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
     * @throws BadMethodCallException
     */
    public function __call(string $name, array $arguments)
    {
        if (in_array($name, self::WRITE_METHODS)) {
            throw new BadMethodCallException("{$name} is write method.");
        }
        if ($this->shouldBuild($name)) {
            $this->build();
        }
        $result = $this->forwardCallTo($this->model, $name, $arguments);
        if (($result instanceof $this->model) || ($result instanceof Builder)) {
            $this->model = $result;
        }
        return $result;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function shouldBuild(string $name)
    {
        return in_array($name, self::READ_METHODS) && !$this->isBuilt;
    }
}
