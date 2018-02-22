<?php

namespace Nodes\Database\Eloquent;

use Closure;
use Illuminate\Database\Eloquent\Builder as IlluminateEloquentBuilder;
use Illuminate\Database\Eloquent\Model as IlluminateEloquentModel;
use Illuminate\Database\Eloquent\Relations\Relation as IlluminateEloquentRelation;
use Illuminate\Database\Eloquent\SoftDeletes as IlluminateEloquentSoftDeletes;
use Illuminate\Database\Query\Builder as IlluminateQueryBuilder;
use Nodes\Database\Exceptions\EntityNotFoundException;
use Nodes\Database\Exceptions\ModelNotSoftDeletable;
use Nodes\Exceptions\Exception as NodesException;

/**
 * Class Repository.
 *
 * @abstract
 *
 * @see \Illuminate\Database\Eloquent\Builder
 * @method IlluminateEloquentBuilder withGlobalScope($identifier, $scope)
 * @method IlluminateEloquentBuilder withoutGlobalScope($scope)
 * @method IlluminateEloquentBuilder withoutGlobalScopes(array $scopes = null)
 * @method void onDelete(Closure $callback)
 * @method IlluminateEloquentModel[] getModels($columns = ['*'])
 * @method array eagerLoadRelations(array $models)
 * @method IlluminateEloquentRelation getRelation($name)
 * @method IlluminateEloquentBuilder has($relation, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null)
 * @method IlluminateEloquentBuilder doesntHave($relation, $boolean = 'and', Closure $callback = null)
 * @method IlluminateEloquentBuilder whereHas($relation, Closure $callback, $operator = '>=', $count = 1)
 * @method IlluminateEloquentBuilder function whereDoesntHave($relation, Closure $callback = null)
 * @method IlluminateEloquentBuilder orHas($relation, $operator = '>=', $count = 1)
 * @method IlluminateEloquentBuilder orWhereHas($relation, Closure $callback, $operator = '>=', $count = 1)
 * @method IlluminateEloquentBuilder applyScopes()
 * @method IlluminateEloquentBuilder applyScope($scope, $builder)
 * @method IlluminateQueryBuilder toBase()
 * @method IlluminateEloquentBuilder setQuery($query)
 * @method array getEagerLoads()
 * @method IlluminateEloquentBuilder setEagerLoads(array $eagerLoad)
 * @method void macro($name, Closure $callback)
 * @method Closure getMacro($name)
 * @method IlluminateEloquentBuilder select($columns = ['*'])
 * @method IlluminateEloquentBuilder selectRaw($expression, array $bindings = [])
 * @method IlluminateEloquentBuilder selectSub($query, $as)
 * @method IlluminateEloquentBuilder addSelect($column)
 * @method IlluminateEloquentBuilder distinct()
 * @method IlluminateEloquentBuilder from($table)
 * @method IlluminateEloquentBuilder join($table, $one, $operator = null, $two = null, $type = 'inner', $where = false)
 * @method IlluminateEloquentBuilder joinWhere($table, $one, $operator, $two, $type = 'inner')
 * @method IlluminateEloquentBuilder leftJoin($table, $first, $operator = null, $second = null)
 * @method IlluminateEloquentBuilder leftJoinWhere($table, $one, $operator, $two)
 * @method IlluminateEloquentBuilder rightJoin($table, $first, $operator = null, $second = null)
 * @method IlluminateEloquentBuilder rightJoinWhere($table, $one, $operator, $two)
 * @method IlluminateEloquentBuilder whereRaw($sql, array $bindings = [], $boolean = 'and')
 * @method IlluminateEloquentBuilder orWhereRaw($sql, array $bindings = [])
 * @method IlluminateEloquentBuilder whereBetween($column, array $values, $boolean = 'and', $not = false)
 * @method IlluminateEloquentBuilder orWhereBetween($column, array $values)
 * @method IlluminateEloquentBuilder whereNotBetween($column, array $values, $boolean = 'and')
 * @method IlluminateEloquentBuilder orWhereNotBetween($column, array $values)
 * @method IlluminateEloquentBuilder whereNested(Closure $callback, $boolean = 'and')
 * @method IlluminateEloquentBuilder forNestedWhere()
 * @method IlluminateEloquentBuilder addNestedWhereQuery($query, $boolean = 'and')
 * @method IlluminateEloquentBuilder whereSub($column, $operator, Closure $callback, $boolean)
 * @method IlluminateEloquentBuilder whereExists(Closure $callback, $boolean = 'and', $not = false)
 * @method IlluminateEloquentBuilder orWhereExists(Closure $callback, $not = false)
 * @method IlluminateEloquentBuilder whereNotExists(Closure $callback, $boolean = 'and')
 * @method IlluminateEloquentBuilder orWhereNotExists(Closure $callback)
 * @method IlluminateEloquentBuilder addWhereExistsQuery(Builder $query, $boolean = 'and', $not = false)
 * @method IlluminateEloquentBuilder whereIn($column, $values, $boolean = 'and', $not = false)
 * @method IlluminateEloquentBuilder orWhereIn($column, $values)
 * @method IlluminateEloquentBuilder whereNotIn($column, $values, $boolean = 'and')
 * @method IlluminateEloquentBuilder orWhereNotIn($column, $values)
 * @method IlluminateEloquentBuilder whereNull($column, $boolean = 'and', $not = false)
 * @method IlluminateEloquentBuilder orWhereNull($column)
 * @method IlluminateEloquentBuilder whereNotNull($column, $boolean = 'and')
 * @method IlluminateEloquentBuilder orWhereNotNull($column)
 * @method IlluminateEloquentBuilder whereDate($column, $operator, $value, $boolean = 'and')
 * @method IlluminateEloquentBuilder orWhereDate($column, $operator, $value)
 * @method IlluminateEloquentBuilder whereDay($column, $operator, $value, $boolean = 'and')
 * @method IlluminateEloquentBuilder whereMonth($column, $operator, $value, $boolean = 'and')
 * @method IlluminateEloquentBuilder whereYear($column, $operator, $value, $boolean = 'and')
 * @method IlluminateEloquentBuilder dynamicWhere($method, $parameters)
 * @method IlluminateEloquentBuilder groupBy()
 * @method IlluminateEloquentBuilder having($column, $operator = null, $value = null, $boolean = 'and')
 * @method IlluminateEloquentBuilder orHaving($column, $operator = null, $value = null)
 * @method IlluminateEloquentBuilder havingRaw($sql, array $bindings = [], $boolean = 'and')
 * @method IlluminateEloquentBuilder orHavingRaw($sql, array $bindings = [])
 * @method IlluminateEloquentBuilder orderBy($column, $direction = 'asc')
 * @method IlluminateEloquentBuilder latest($column = 'created_at')
 * @method IlluminateEloquentBuilder oldest($column = 'created_at')
 * @method IlluminateEloquentBuilder orderByRaw($sql, $bindings = [])
 * @method IlluminateEloquentBuilder offset($value)
 * @method IlluminateEloquentBuilder skip($value)
 * @method IlluminateEloquentBuilder limit($value)
 * @method IlluminateEloquentBuilder take($value)
 * @method IlluminateEloquentBuilder forPage($page, $perPage = 15)
 * @method IlluminateEloquentBuilder union($query, $all = false)
 * @method IlluminateEloquentBuilder unionAll($query)
 * @method IlluminateEloquentBuilder lock($value = true)
 * @method IlluminateEloquentBuilder lockForUpdate()
 * @method IlluminateEloquentBuilder sharedLock()
 */
abstract class Repository
{
    /**
     * Repository model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Repository builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * setupRepository.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function setupRepository(IlluminateEloquentModel $model)
    {
        $this->setModel($model);
    }

    /**
     * Initiates a new model instance
     * and populate it's attributes with provided data.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array $attributes Array of data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newInstance(array $attributes = [])
    {
        return $this->getModel()->newInstance($attributes);
    }

    /**
     * Generate a new instance and saves it.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array $attributes Array of data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        return $this->getModel()->create($attributes);
    }

    /**
     * Set the relationships that should be eager loaded.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  mixed $relations
     * @return $this
     */
    public function with($relations)
    {
        // Eager load relations
        $this->getBuilder()->with($relations);

        return $this;
    }

    /**
     * Execute the query and get the first result.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first($columns = ['*'])
    {
        $result = $this->getBuilder()->first($columns);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Execute the query and get the first result or throw an exception
     *l.
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     */
    public function firstOrFail($columns = ['*'])
    {
        $result = $this->first($columns);
        if (empty($result)) {
            throw new EntityNotFoundException(sprintf('First record for table [%s] not found', get_class($this->model)));
        }

        return $result;
    }

    /**
     * Execute the query and retrieve result.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = ['*'])
    {
        $result = $this->getBuilder()->get($columns);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  int $perPage
     * @param  array   $columns
     * @param  string  $pageName
     * @param  int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $result = $this->getBuilder()->paginate($perPage, $columns, $pageName, $page);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Get a paginator only supporting simple next and previous links.
     *
     * This is more efficient on larger data-sets, etc.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  int $perPage
     * @param  array   $columns
     * @param  string  $pageName
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($perPage = 15, $columns = ['*'], $pageName = 'page')
    {
        $result = $this->getBuilder()->simplePaginate($perPage, $columns, $pageName);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Get the count of the total records for the paginator.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array $columns
     * @return int
     */
    public function getCountForPagination($columns = ['*'])
    {
        $result = $this->getBuilder()->getCountForPagination($columns);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Get a single column's value from the first result of a query.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $column
     * @return mixed
     */
    public function value($column)
    {
        $result = $this->getBuilder()->value($column);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Chunk the results of the query.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  int  $count
     * @param  callable $callback
     * @return bool
     */
    public function chunk($count, callable $callback)
    {
        $result = $this->getBuilder()->chunk($count, $callback);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Execute a callback over each item while chunking.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  callable $callback
     * @param  int  $count
     * @return bool
     * @throws \RuntimeException
     */
    public function each(callable $callback, $count = 1000)
    {
        $result = $this->getBuilder()->each($callback, $count);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Get an array with the values of a given column.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string  $column
     * @param  string  $key
     * @return array
     */
    public function pluck($column, $key = null)
    {
        $result = $this->getBuilder()->pluck($column, $key);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Alias for the "pluck" method.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string  $column
     * @param  string  $key
     * @return array
     */
    public function lists($column, $key = null)
    {
        return $this->pluck($column, $key);
    }

    /**
     * Concatenate values of a given column as a string.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string  $column
     * @param  string  $glue
     * @return string
     */
    public function implode($column, $glue = '')
    {
        $result = $this->getBuilder()->implode($column, $glue);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Determine if any rows exist for the current query.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return bool
     */
    public function exists()
    {
        $result = $this->getBuilder()->exists();

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Execute query as a count statement.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $columns
     * @return int
     */
    public function count($columns = '*')
    {
        $result = $this->getBuilder()->count($columns);

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Retrieve the minimum value of a given column.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $column
     * @return float|int
     */
    public function min($column)
    {
        $result = $this->getBuilder()->min($column);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Retrieve the maximum value of a given column.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $column
     * @return float|int
     */
    public function max($column)
    {
        $result = $this->getBuilder()->max($column);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Retrieve the sum of the values of a given column.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $column
     * @return float|int
     */
    public function sum($column)
    {
        $result = $this->getBuilder()->sum($column);

        // Reset query builder
        $this->resetBuilder();

        return $result ?: 0;
    }

    /**
     * Retrieve the average of the values of a given column.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $column
     * @return float|int
     */
    public function avg($column)
    {
        $result = $this->getBuilder()->avg($column);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Alias for the "avg" method.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $column
     * @return float|int
     */
    public function average($column)
    {
        return $this->avg($column);
    }

    /**
     * Execute an aggregate function on the database.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $function
     * @param  array  $columns
     * @return float|int
     */
    public function aggregate($function, $columns = ['*'])
    {
        $result = $this->getBuilder()->aggregate($function, $columns);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Insert a new record into the database.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array $values
     * @return bool
     */
    public function insert(array $values)
    {
        $result = $this->getBuilder()->insert($values);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Insert a new record and get the value of the primary key.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array  $values
     * @param  string $sequence
     * @return int
     */
    public function insertGetId(array $values, $sequence = null)
    {
        $result = $this->getBuilder()->insertGetId($values, $sequence);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Execute query as an update statement.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array $values
     * @return int
     */
    public function update(array $values)
    {
        $result = $this->getBuilder()->update($values);

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Execute query as a delete statement.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return int
     */
    public function delete()
    {
        $result = $this->getBuilder()->delete();

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Execute query as a (force) delete statement.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return int
     */
    public function forceDelete()
    {
        $result = $this->getBuilder()->forceDelete();

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Increment a column's value by a given amount.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string  $column
     * @param  int $amount
     * @param  array   $extra
     * @return int
     */
    public function increment($column, $amount = 1, array $extra = [])
    {
        $result = $this->getBuilder()->increment($column, $amount, $extra);

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Decrement a column's value by a given amount.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string  $column
     * @param  int $amount
     * @param  array   $extra
     * @return int
     */
    public function decrement($column, $amount = 1, array $extra = [])
    {
        $result = $this->getBuilder()->decrement($column, $amount, $extra);

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Run a truncate statement on the table.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    public function truncate()
    {
        $this->getBuilder()->truncate();

        // Reset query builder
        $this->resetBuilder();
    }

    /**
     * Create a raw database expression.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Query\Expression
     */
    public function raw($value)
    {
        $result = $this->getBuilder()->raw($value);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Get the SQL representation of the query.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return string
     */
    public function toSql()
    {
        $result = $this->getBuilder()->toSql();

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Render repository's query SQL string.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return string
     */
    public function renderSql()
    {
        $result = vsprintf(
            $this->getBuilder()->getQuery()->toSql(),
            $this->getBuilder()->getQuery()->getBindings()
        );

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Include soft deleted entries in query.
     * Note this will reset the build
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @throws \Nodes\Database\Exceptions\ModelNotSoftDeletable
     */
    public function withTrashed()
    {
        // Validate model is soft deletable
        if (! in_array(IlluminateEloquentSoftDeletes::class, class_uses($this->getModel()))) {
            throw new ModelNotSoftDeletable('Model [%s] is not using the Soft Delete trait');
        }

        // Set repository builder to include soft deleted entries
        $this->setBuilder($this->getModel()->withTrashed());

        return $this;
    }

    /**
     * Only include soft deleted entries in query.
     * Note this will reset the build
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return $this
     * @throws \Nodes\Database\Exceptions\ModelNotSoftDeletable
     */
    public function onlyTrashed()
    {
        // Validate model is soft deletable
        if (! in_array(IlluminateEloquentSoftDeletes::class, class_uses($this->getModel()))) {
            throw new ModelNotSoftDeletable('Model [%s] is not using the Soft Delete trait');
        }

        // Set repository builder to include soft deleted entries
        $this->setBuilder($this->getModel()->onlyTrashed());

        return $this;
    }

    /**
     * Find a model by its primary key.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public function find($id, array $columns = ['*'])
    {
        if (is_array($id)) {
            return $this->findMany($id, $columns);
        }

        return $this->getBy($this->getModel()->getQualifiedKeyName(), $id, $columns);
    }

    /**
     * Find a model by its primary key.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  array  $ids
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findMany($ids, array $columns = ['*'])
    {
        if (empty($ids)) {
            return $this->model->newCollection();
        }

        $this->getBuilder()->whereIn($this->getModel()->getQualifiedKeyName(), $ids);

        return $this->get($columns);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $result = $this->find($id, $columns);

        if (is_array($id)) {
            if (count($result) == count(array_unique($id))) {
                return $result;
            }
        } elseif (! is_null($result)) {
            return $result;
        }

        throw new EntityNotFoundException('Entity not found');
    }

    /**
     * Retrieve entity by a specific column and value.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string         $column
     * @param  string|int $value
     * @param  array          $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getBy($column, $value, array $columns = ['*'])
    {
        $result = $this->getBuilder()
                       ->select($columns)
                       ->where($column, '=', $value)
                       ->first();

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Retrieve entity by a specific column and value.
     *
     * If entity is not found, we'll throw an exception
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string         $column
     * @param  string|int $value
     * @param  array          $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     */
    public function getByOrFail($column, $value, array $columns = ['*'])
    {
        $entity = $this->getBy($column, $value, $columns);
        if (empty($entity)) {
            throw new EntityNotFoundException(sprintf('%s not found for column [%s] with value [%s]',
                get_class($this->getModel()), $column, $value));
        }

        return $entity;
    }

    /**
     * Retrieve entity by ID.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  int $id
     * @param  array   $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id, array $columns = ['*'])
    {
        return $this->getBy('id', $id, $columns);
    }

    /**
     * Retrieve entity by ID.
     *
     * If entity is not found, we'll throw an exception
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  int $id
     * @param  array   $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     */
    public function getByIdOrFail($id, array $columns = ['*'])
    {
        return $this->getByOrFail('id', $id, $columns);
    }

    /**
     * Retrieve entity by ID
     * (including soft deleted entries).
     *
     * If entity is not found, we'll throw an exception
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  int $id
     * @param  array   $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws \Nodes\Database\Exceptions\ModelNotSoftDeletable
     */
    public function getByIdWithTrashed($id, array $columns = ['*'])
    {
        return $this->withTrashed()->getById($id, $columns);
    }

    /**
     * Retrieve entity by ID
     * (including soft deleted entries).
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  int $id
     * @param  array   $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\ModelNotSoftDeletable
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     */
    public function getByIdWithTrashedOrFail($id, array $columns = ['*'])
    {
        return $this->withTrashed()->getByIdOrFail($id, $columns);
    }

    /**
     * Retrieve entity by a specific column and value. Will retry with a delay until found.
     *
     * Should only be used in queues, where databases are in a cluster
     * and there's a chance it's not always in sync
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @param  string  $column
     * @param  string  $value
     * @param  array   $columns
     * @param  int $retries
     * @param  int $delayMs
     * @param  int $maxDelay
     * @param  int $maxRetries
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Exceptions\Exception
     */
    public function getByContinuously($column, $value, array $columns = ['*'], $retries = 10, $delayMs = 100, $maxDelay = 2000, $maxRetries = 100)
    {
        // Hard limits
        $maxDelay = ($maxDelay > 2000) ? 2000 : $maxDelay;
        $maxRetries = ($maxRetries > 100) ? 100 : $maxRetries;

        // Validate delay parameter
        if ($delayMs > $maxDelay) {
            throw new NodesException('Invalid input parameter. Maximum delay is '.$maxDelay.' milliseconds', 0, null, false);
        }

        // Validate retry parameter
        if ($retries > $maxRetries) {
            throw new NodesException('Invalid input parameter. Maximum retry amount is '.$maxRetries, 0, null, false);
        }

        // Retrieve entity continuously
        for ($try = 0; $try < $retries; $try++) {
            $entity = $this->getBy($column, $value, $columns);
            if (! empty($entity)) {
                return $entity;
            }

            // Delay next retry
            usleep($delayMs * 1000);
        }

        return false;
    }

    /**
     * Retrieve entity by ID. Will retry with a delay until found.
     *
     * Should only be used in queues, where databases are in a cluster
     * and there's a chance it's not always in sync
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @param  int $id
     * @param  array   $columns
     * @param  int $retries
     * @param  int $delayMs
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Exceptions\Exception
     */
    public function getByIdContinuously($id, array $columns = ['*'], $retries = 10, $delayMs = 100)
    {
        return $this->getByContinuously('id', $id, $columns, $retries, $delayMs);
    }

    /**
     * Retrieve entity by ID. Will retry with a delay until found or throw an exception.
     *
     * Should only be used in queues, where databases are in a cluster
     * and there's a chance it's not always in sync
     *
     * @author Rasmus Ebbesen <re@nodes.dk>
     *
     * @param  int $id
     * @param  array   $columns
     * @param  int $retries
     * @param  int $delayMs
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     */
    public function getByIdContinuouslyOrFail($id, array $columns = ['*'], $retries = 10, $delayMs = 100)
    {
        $result = $this->getByContinuously('id', $id, $columns, $retries, $delayMs);
        if (empty($result)) {
            throw new EntityNotFoundException(sprintf('%s not found continuously by Id with value [%s]',
                get_class($this->getModel()), $id));
        }

        return $result;
    }

    /**
     * Delete morphed relations by entity.
     *
     * Note: This should only be used with morphed relations
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  \Illuminate\Database\Eloquent\Model $entity
     * @param  string                              $relationName
     * @param  bool                             $forceDelete
     * @return int
     */
    public function deleteMorphsByEntity(IlluminateEloquentModel $entity, $relationName, $forceDelete = false)
    {
        // Retrieve all records by entity type and entity ID
        $entities = $this->getBuilder()
                         ->select(['id'])
                         ->where(function ($query) use ($entity, $relationName) {
                             $query->where($relationName.'_type', '=', get_class($entity))
                                   ->where($relationName.'_id', '=', (int) $entity->id);
                         })
                         ->get();

        // Delete count
        $deleteCount = 0;

        // Loop through each entity individually.
        // This is required to soft delete all found entiries.
        foreach ($entities as $e) {
            $status = ($forceDelete) ? $e->forceDelete() : $e->delete();
            if ((bool) $status) {
                $deleteCount += 1;
            }
        }

        // Reset query builder
        $this->resetBuilder();

        return $deleteCount;
    }

    /**
     * Restore morphed relations by entity.
     *
     * Note: This should only be used with morphed relations
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  \Illuminate\Database\Eloquent\Model $entity
     * @param  string                              $relationName
     * @return int
     * @throws \Nodes\Database\Exceptions\ModelNotSoftDeletable
     */
    public function restoreMorphsByEntity(IlluminateEloquentModel $entity, $relationName)
    {
        // Validate model is soft deletable
        if (! in_array(IlluminateEloquentSoftDeletes::class, class_uses($this->getModel()))) {
            throw new ModelNotSoftDeletable('Model [%s] is not using the Soft Delete trait');
        }

        // Retrieve all records by entity type and entity ID
        $entities = $this->onlyTrashed()
                         ->getBuilder()
                         ->select(['id'])
                         ->where(function ($query) use ($entity, $relationName) {
                             $query->where($relationName.'_type', '=', get_class($entity))
                                  ->where($relationName.'_id', '=', (int) $entity->id);
                         })
                         ->get();

        // Restore count
        $restoreCount = 0;

        // Loop through each entity individually.
        // This is required to soft delete all found entiries.
        foreach ($entities as $e) {
            if ((bool) $e->restore()) {
                $restoreCount += 1;
            }
        }

        // Reset query builder
        $this->resetBuilder();

        return $restoreCount;
    }

    /**
     * Begin transaction.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    public function beginTransaction()
    {
        return $this->getBuilder()->getQuery()->getConnection()->beginTransaction();
    }

    /**
     * Commit transaction.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    public function commitTransaction()
    {
        return $this->getBuilder()->getQuery()->getConnection()->commit();
    }

    /**
     * Rollback transaction.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    public function rollbackTransaction()
    {
        return $this->getBuilder()->getQuery()->getConnection()->rollBack();
    }

    /**
     * Set repository model.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return $this
     */
    public function setModel(IlluminateEloquentModel $model)
    {
        // Set repository model
        $this->model = $model;

        // Set repository builder from model
        $this->setBuilder($model->newQuery());

        return $this;
    }

    /**
     * Retrieve repository model.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set repository builder.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @acecss public
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return $this
     */
    public function setBuilder(IlluminateEloquentBuilder $builder = null)
    {
        // If no builder was provided,
        // we'll use the one from this repositorys model
        if (empty($builder)) {
            $builder = $this->getModel()->newQuery();
        }

        // Set repository builder
        $this->builder = $builder;

        return $this;
    }

    /**
     * Retrieve repository builder.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Reset repository builder.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return $this
     */
    public function resetBuilder()
    {
        // Generate a new query builder from repositorys model
        $this->setBuilder($this->getModel()->newQuery());

        return $this;
    }

    /**
     * Handle dynamic method calls into the repository builder.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $method
     * @param  array  $parameters
     * @return $this
     */
    public function __call($method, $parameters)
    {
        call_user_func_array([$this->getBuilder(), $method], $parameters);

        return $this;
    }
}
