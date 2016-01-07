<?php
namespace Nodes\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Database\Eloquent\Builder as IlluminateEloquentBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Nodes\Exceptions\Exception as NodesException;
use Nodes\Database\Exceptions\EntityNotFoundException;

/**
 * Class Repository
 *
 * @abstract
 * @package Nodes\Database\Eloquent
 */
abstract class Repository extends IlluminateEloquentBuilder
{
    /**
     * Setup repostitory to work with provided model
     *
     * @author Morten Rugaard <moru@odes.dk>
     *
     * @access public
     * @param  \Illuminate\Database\Eloquent\Model $model
     */
    public function setupRepository(IlluminateModel $model)
    {
        $this->setBuilder($model->newQuery()->getQuery());
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
        return $this->model->newInstance($attributes);
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
        return $this->model->create($attributes);
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
        return $this->select($columns)
            ->where($column, '=', $value)
            ->first();
    }

    /**
     * Retrieve entity by a specific column and value
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
     * Retrieve entity it's ID
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
        return $this->getBy('id', (int) $id, $columns);
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
     * Retrieve entity by ID.
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
        return $this->getByOrFail('id', (int) $id, $columns);
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
        $item = parent::first($columns);

        // Reset query builder
        $this->resetBuilder();

        return $item;
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
        $item = $this->first($columns);
        if (empty($item)) {
            throw new EntityNotFoundException('Entity not found');
        }

        return $item;
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
        $data = parent::get($columns);

        // Reset query builder
        $this->resetBuilder();

        return $data;
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
        $count = parent::count($columns);

        // Reset query builder
        $this->resetBuilder();

        return (int) $count;
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
        $results = parent::lists($column, $key);

        // Reset query builder
        $this->resetBuilder();

        return $results;
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
        $result = parent::update($values);

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Execute query as a delete statement
     *
     * Note: If using Laravel's soft delete trait
     * this will actually be executed as an update statement
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return integer
     */
    public function delete()
    {
        $result = parent::delete();

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
        $result = parent::forceDelete();

        // Reset query builder
        $this->resetBuilder();

        return (int) $result;
    }

    /**
     * Delete by entity
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
    public function deleteAllByEntity(IlluminateModel $entity, $relationName, $forceDelete = false)
    {
        // Retrieve all records by entity type and entity ID
        $entities = $this->select(['id'])
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
        $result = parent::increment($column, $amount, $extra);

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
        $result = parent::decrement($column, $amount, $extra);

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
     * @return $this
     */
    public function withTrashed()
    {
        // Make sure model implements the soft delete trait
        if (!in_array(SoftDeletes::class, class_uses($this->getModel()))) {
            return $this;
        }

        // Include soft deleted entries in query
        $this->setQuery(
            $this->getModel()->withTrashed()->getQuery()
        );

        return $this;
    }

    /**
     * Only return soft deleted entries in query
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return $this
     */
    public function onlyTrashed()
    {
        // Make sure model implements the soft delete trait
        if (!in_array(SoftDeletes::class, class_uses($this->getModel()))) {
            return $this;
        }

        // Only include soft deleted entries in query
        $this->setQuery(
            $this->getModel()->onlyTrashed()->getQuery()
        );

        return $this;
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
        return $this->getBuilder()->getConnection()->beginTransaction();
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
        return $this->getBuilder()->getConnection()->commit();
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
        return $this->getBuilder()->getConnection()->rollBack();
    }

    /**
     * Set model of repository
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Nodes\Database\Eloquent\Repository
     */
    public function setModel(IlluminateModel $model)
    {
        parent::setModel($model);
        return $this;
    }

    /**
     * Retrieve model of repository
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
     * Set repositorys query builder
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  \Illuminate\Database\Query\Builder $query
     * @return $this
     */
    public function setBuilder(QueryBuilder $query)
    {
        parent::setQuery($query);
        return $this;
    }

    /**
     * Retrieve repository's query builder
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return \Illuminate\Database\Query\Builder
     */
    public function getBuilder()
    {
        return $this->query;
    }

    /**
     * Reset query builder
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return $this
     */
    public function resetBuilder()
    {
        // Generate new query builder
        $builder = $this->getModel()->newQuery()->getQuery();
        $this->setBuilder($builder);
        return $this;
    }

    /**
     * Generated SQL with inserted bindings
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  \Illuminate\Database\Query\Builder $query
     * @return string
     */
    public function renderSql(QueryBuilder $query)
    {
        return vsprintf($query->toSql(), $query->getBindings());
    }
}