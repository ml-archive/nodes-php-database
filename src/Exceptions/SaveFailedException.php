<?php

namespace Nodes\Database\Exceptions;

use Nodes\Exceptions\Exception as NodesException;

/**
 * Class SaveFailedException.
 */
class SaveFailedException extends NodesException
{
    protected $previousException;

    /**
     * SaveFailedException constructor
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @access public
     * @param string $message
     * @param int    $code
     * @param array  $headers
     * @param bool   $report
     * @param string $severity
     */
    public function __construct(
        $message = null,
        $code = 550,
        array $headers = [],
        $report = true,
        $severity = 'error'
    ) {
        parent::__construct($message, $code, $headers, $report, $severity);
        $this->setPreviousExceptionMeta();

        // Set status code and status message
        $this->setStatusCode(550, 'Could not save to database');
    }

    /**
     * setPreviousException
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @access public
     * @param \Exception|\Throwable $e
     * @return $this
     */
    public function setPreviousException($e)
    {
        $this->previousException = $e;

        $this->setPreviousExceptionMeta();

        return $this;
    }

    /**
     * setPreviousExceptionMeta
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @access private
     * @return void
     */
    private function setPreviousExceptionMeta()
    {
        if ($exception = $this->previousException) {
            $this->meta['previous_exception'] = [
                'message'  => $exception->getMessage(),
                'file'     => $exception->getFile(),
                'line'     => $exception->getLine(),
                'trace'    => $exception->getTrace(),
            ];
        }
    }
}
