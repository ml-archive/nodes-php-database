<?php
namespace Nodes\Database\Eloquent;

use Exception;
use Throwable;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Nodes\Database\Exceptions\SaveFailedException;

/**
 * Class Model
 *
 * @package Nodes\Database\Eloquent
 */
class Model extends IlluminateModel
{
    /**
     * Save the model to the database
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $options
     * @return boolean
     * @throws \Nodes\Database\Exceptions\SaveFailedException
     */
    public function save(array $options = [])
    {
        try {
            // Save model to database
            $result = parent::save($options);

            // If save has been aborted from an event/observer
            // or simply returned an empty response, we'll treat
            // it as a failed save and throw an exception.
            if (!$result) {
                throw new SaveFailedException(sprintf('Could not save model [%s]. Reason: Save returned false.', get_class($this)));
            }
        } catch (Exception $e) {
            // Catch exceptions and re-throw
            // as our Nodes "save failed" exceptions
            throw new SaveFailedException(sprintf('Could not save model [%s]. Reason: %s', get_class($this), $e->getMessage()));
        } catch (Throwable $e) {
            // Add support for PHP 7 throwable interface.
            // Re-throw as our Nodes "save failed" exceptions.
            throw new SaveFailedException(sprintf('Could not save model [%s]. Reason: %s', get_class($this), $e->getMessage()));
        }

        return $result;
    }
}