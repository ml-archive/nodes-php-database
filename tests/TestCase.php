<?php
namespace Nodes\Database\Tests;

use Mockery;
use Illuminate\Database\Eloquent\Model;
use Nodes\Database\Eloquent\Repository;
use Orchestra\Testbench\TestCase as OrchestraTestbench;

class TestCase extends OrchestraTestbench
{
    protected $model;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->model = Mockery::mock(Model::class)->makePartial();
    }

    /**
     * Test if model is an Illuminate model
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function testIsIlluminateModel()
    {
        $this->assertInstanceOf(Model::class, $this->model);
    }

    /**
     * Test that repository loads Illuminate model
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function testRepositoryLoadsIlluminateModel()
    {
        $repository = Mockery::mock(Repository::class)->makePartial();

        $repository->setupRepository($this->model);

        $this->assertInstanceOf(Model::class, $repository->getModel());
    }
}