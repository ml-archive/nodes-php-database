<?php
namespace Nodes\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder as IlluminateEloquentBuilder;
use Illuminate\Database\Eloquent\Model as IlluminateEloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes as IlluminateEloquentSoftDeletes;
use Nodes\Database\Exceptions\EntityNotFoundException;
use Nodes\Database\Exceptions\ModelNotSoftDeletable;
use Nodes\Exceptions\Exception as NodesException;

/**
 * Class Repository
 *
 * @abstract
 * @package Nodes\Database\Eloquent
 */
abstract class Repository
{
    /**
     * Repository model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Repository builder
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * setupRepository
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function setupRepository(IlluminateEloquentModel $model)
    {
        $this->setModel($model);
    }

    /**
     * Initiates a new model instance
     * and populate it's attributes with provided data
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $attributes Array of data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newInstance(array $attributes = [])
    {
        return $this->getModel()->newInstance($attributes);
    }
    /**
     * Generate a new instance and saves it
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $attributes Array of data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        return $this->getModel()->create($attributes);
    }

    /**
     * Execute the query and get the first result
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
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
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     */
    public function firstOrFail($columns = ['*'])
    {
        $result = $this->first($columns);
        if (empty($result)) {
            throw new EntityNotFoundException('Entity not found');
        }

        return $result;
    }

    /**
     * Execute the query and retrieve result
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
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
     * Execute query as a count statement
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string $columns
     * @return integer
     */
    public function count($columns = '*')
    {
        $result = $this->getBuilder()->count($columns);

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Execute the query and retrieve an array
     * with the values of a given column
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string  $column
     * @param  string  $key
     * @return array
     */
    public function lists($column, $key = null)
    {
        $result = $this->getBuilder()->lists($column, $key);

        // Reset query builder
        $this->resetBuilder();

        return $result;
    }

    /**
     * Execute query as an update statement
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $values
     * @return integer
     */
    public function update(array $values)
    {
        $result = $this->getBuilder()->update($values);

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Execute query as a delete statement
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return integer
     */
    public function delete()
    {
        $result = $this->getBuilder()->delete();

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Execute query as a (force) delete statement
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return integer
     */
    public function forceDelete()
    {
        $result = $this->getBuilder()->forceDelete();

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Increment a column's value by a given amount
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string  $column
     * @param  integer $amount
     * @param  array   $extra
     * @return integer
     */
    public function increment($column, $amount = 1, array $extra = array())
    {
        $result = $this->getBuilder()->increment($column, $amount, $extra);

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Decrement a column's value by a given amount
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string  $column
     * @param  integer $amount
     * @param  array   $extra
     * @return integer
     */
    public function decrement($column, $amount = 1, array $extra = array())
    {
        $result = $this->getBuilder()->decrement($column, $amount, $extra);

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Include soft deleted entries in query
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @throws \Nodes\Database\Exceptions\ModelNotSoftDeletable
     */
    public function withTrashed()
    {
        // Validate model is soft deletable
        if (!in_array(IlluminateEloquentSoftDeletes::class, class_uses($this->getModel()))) {
            throw new ModelNotSoftDeletable('Model [%s] is not using the Soft Delete trait');
        }

        // Set repository builder to include soft deleted entries
        $this->setBuilder($this->getModel()->withTrashed());

        return $this;
    }

    /**
     * Only include soft deleted entries in query
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return $this
     * @throws \Nodes\Database\Exceptions\ModelNotSoftDeletable
     */
    public function onlyTrashed()
    {
        // Validate model is soft deletable
        if (!in_array(IlluminateEloquentSoftDeletes::class, class_uses($this->getModel()))) {
            throw new ModelNotSoftDeletable('Model [%s] is not using the Soft Delete trait');
        }

        // Set repository builder to include soft deleted entries
        $this->setBuilder($this->getModel()->onlyTrashed());

        return $this;
    }

    /**
     * Retrieve entity by a specific column and value
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string         $column
     * @param  string|integer $value
     * @param  array          $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getBy($column, $value, array $columns = ['*'])
    {
        return $this->getBuilder()
            ->select($columns)
            ->where($column, '=', $value)
            ->first();
    }

    /**
     * Retrieve entity by a specific column and value
     *
     * If entity is not found, we'll throw an exception
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string         $column
     * @param  string|integer $value
     * @param  array          $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\EntityNotFoundException
     */
    public function getByOrFail($column, $value, array $columns = ['*'])
    {
        $entity = $this->getBy($column, $value, $columns);
        if (empty($entity)) {
            throw new EntityNotFoundException('Entity not found');
        }

        return $entity;
    }

    /**
     * Retrieve entity by ID
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  integer $id
     * @param  array   $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id, array $columns = ['*'])
    {
        return $this->getBy('id', $id, $columns);
    }

    /**
     * Retrieve entity by ID
     *
     * If entity is not found, we'll throw an exception
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  integer $id
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
     * (including soft deleted entries)
     *
     * If entity is not found, we'll throw an exception
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  integer $id
     * @param  array   $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\ModelNotSoftDeletable
     */
    public function getByIdWithTrashed($id, array $columns = ['*'])
    {
        return $this->withTrashed()->getById($id, $columns);
    }

    /**
     * Retrieve entity by ID
     * (including soft deleted entries)
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  integer $id
     * @param  array   $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Database\Exceptions\ModelNotSoftDeletable
     */
    public function getByIdWithTrashedOrFail($id, array $columns = ['*'])
    {
        return $this->withTrashed()->getByIdOrFail($id, $columns);
    }

    /**
     * Retrieve entity by a specific column and value. Will retry with a delay until found
     *
     * Should only be used in queues, where databases are in a cluster
     * and there's a chance it's not always in sync
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @access public
     * @param  string  $column
     * @param  string  $value
     * @param  array   $columns
     * @param  integer $retries
     * @param  integer $delayMs
     * @param  integer $maxDelay
     * @param  integer $maxRetries
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
            throw new NodesException('Invalid input parameter. Maximum delay is ' . $maxDelay . ' milliseconds', 0, null, false);
        }

        // Validate retry parameter
        if ($retries > $maxRetries) {
            throw new NodesException('Invalid input parameter. Maximum retry amount is ' . $maxRetries, 0, null, false);
        }

        // Retrieve entity continuously
        for ($try = 0; $try < $retries; $try++) {
            $entity = $this->getBy($column, $value, $columns);
            if (!empty($entity)) {
                return $entity;
            }

            // Delay next retry
            usleep($delayMs * 1000);
        }

        return false;
    }

    /**
     * Retrieve entity by ID. Will retry with a delay until found
     *
     * Should only be used in queues, where databases are in a cluster
     * and there's a chance it's not always in sync
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @access public
     * @param  integer $id
     * @param  array   $columns
     * @param  integer $retries
     * @param  integer $delayMs
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Exceptions\Exception
     */
    public function getByIdContinuously($id, array $columns = ['*'], $retries = 10, $delayMs = 100)
    {
        return $this->getByContinuously('id', $id, $columns, $retries, $delayMs);
    }

    /**
     * Retrieve entity by ID. Will retry with a delay until found or throw an exception
     *
     * Should only be used in queues, where databases are in a cluster
     * and there's a chance it's not always in sync
     *
     * @author Rasmus Ebbesen <re@nodes.dk>
     *
     * @access public
     * @param  integer $id
     * @param  array   $columns
     * @param  integer $retries
     * @param  integer $delayMs
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Nodes\Exceptions\Exception
     */
    public function getByIdContinuouslyOrFail($id, array $columns = ['*'], $retries = 10, $delayMs = 100)
    {
        $result = $this->getByContinuously('id', $id, $columns, $retries, $delayMs);
        if (!empty($result)) {
            throw new EntityNotFoundException('Entity not found');
        }

        return $result;
    }

    /**
     * Delete morphed relations by entity
     *
     * Note: This should only be used with morphed relations
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  \Illuminate\Database\Eloquent\Model $entity
     * @param  string                              $relationName
     * @param  boolean                             $forceDelete
     * @return integer
     */
    public function deleteMorphsByEntity(IlluminateEloquentModel $entity, $relationName, $forceDelete = false)
    {
        // Retrieve all records by entity type and entity ID
        $entities = $this->getBuilder()
                         ->select(['id'])
                         ->where(function($query) use ($entity, $relationName) {
                             $query->where($relationName . '_type', '=', get_class($entity))
                                   ->where($relationName . '_id', '=', (int) $entity->id);
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
     * Restore morphed relations by entity
     *
     * Note: This should only be used with morphed relations
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  \Illuminate\Database\Eloquent\Model $entity
     * @param  string                              $relationName
     * @return integer
     * @throws \Nodes\Database\Exceptions\ModelNotSoftDeletable
     */
    public function restoreMorphsByEntity(IlluminateEloquentModel $entity, $relationName)
    {
        // Validate model is soft deletable
        if (!in_array(IlluminateEloquentSoftDeletes::class, class_uses($this->getModel()))) {
            throw new ModelNotSoftDeletable('Model [%s] is not using the Soft Delete trait');
        }

        // Retrieve all records by entity type and entity ID
        $entities = $this->onlyTrashed()
                         ->getBuilder()
                         ->select(['id'])
                         ->where(function($query) use ($entity, $relationName) {
                             $query->where($relationName . '_type', '=', get_class($entity))
                                  ->where($relationName . '_id', '=', (int) $entity->id);
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
     * Begin transaction
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function beginTransaction()
    {
        return $this->getBuilder()->getQuery()->getConnection()->beginTransaction();
    }

    /**
     * Commit transaction
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function commitTransaction()
    {
        return $this->getBuilder()->getQuery()->getConnection()->commit();
    }

    /**
     * Rollback transaction
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function rollbackTransaction()
    {
        return $this->getBuilder()->getQuery()->getConnection()->rollBack();
    }

    /**
     * Set repository model
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
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
     * Retrieve repository model
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set repository builder
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
     * Retrieve repository builder
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Reset repository builder
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return $this
     */
    public function resetBuilder()
    {
        // Generate a new repository builder
        // from this repositorys model
        $this->setBuilder($this->getModel()->newQuery());

        return $this;
    }

    /**
     * Render repository's query SQL string
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return string
     */
    public function renderSql()
    {
        return vsprintf(
            $this->getBuilder()->getQuery()->toSql(),
            $this->getBuilder()->getQuery()->getBindings()
        );
    }

    /**
     * Handle dynamic method calls into the repository builder
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // Let's start off by checking if method exists
        // on our repository builder.
        if (method_exists($this->getBuilder(), $method)) {
            return call_user_func_array([$this->getBuilder(), $method], $parameters);
        }

        // Otherwise we'll assume the method exists on our model.
        // If not, it'll throw an exception/error for the user
        return call_user_func_array([$this->getModel(), $method], $parameters);
    }
}