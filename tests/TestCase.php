<?php

namespace Nodes\Database\Tests;

use Mockery;
use Illuminate\Database\Eloquent\Model;
use Nodes\Database\Eloquent\Repository;
use Orchestra\Testbench\TestCase as OrchestraTestbench;

class TestCase extends OrchestraTestbench
{
    /**
     * Mocked Illuminate model class.
     *
     * @var \Mockery\MockInterface
     */
    protected $model;

    /**
     * Mocked Nodes repository class.
     *
     * @var \Mockery\MockInterface
     */
    protected $repository;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->model = Mockery::mock(Model::class)->makePartial();

        $this->repository = Mockery::mock(Repository::class)->makePartial();
        $this->repository->setupRepository($this->model);
    }

    /**
     * Test if model is an Illuminate model.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    public function testIsIlluminateModel()
    {
        $this->assertInstanceOf(Model::class, $this->model);
    }

    /**
     * Test that repository loads Illuminate model.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    public function testRepositoryReturnsIlluminateModel()
    {
        $this->assertInstanceOf(Model::class, $this->repository->getModel());
    }
}
