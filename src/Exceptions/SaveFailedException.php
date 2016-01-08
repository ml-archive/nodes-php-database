<?php
namespace Nodes\Database\Exceptions;

use Nodes\Exceptions\Exception as NodesException;

/**
 * Class SaveFailedException
 *
 * @package Nodes\Database\Exceptions
 */
class SaveFailedException extends NodesException
{
    /**
     * SaveFailedException constructor
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string   $message
     * @param  integer  $code
     * @param  array    $headers
     * @param  boolean  $report
     * @param  string   $severity
     */
    public function __construct($message, $code = 550, array $headers = [], $report = true, $severity = 'error')
    {
        parent::__construct($message, $code, $headers, $report, $severity);

        // Set status code and status message
        $this->setStatusCode(550, 'Could not save to database');
    }
}