<?php
namespace Nodes\Database\Exceptions;

use Nodes\Exceptions\Exception as NodesException;

/**
 * Class ModelNotSoftDeletable
 *
 * @package Nodes\Database\Exceptions
 */
class ModelNotSoftDeletable extends NodesException
{
    /**
     * ModelNotSoftDeletable constructor
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
    public function __construct($message, $code = 500, array $headers = [], $report = true, $severity = 'error')
    {
        parent::__construct($message, $code, $headers, $report, $severity);

        // Set status code and status message
        $this->setStatusCode(500, 'Model does not have soft delete support');
    }
}