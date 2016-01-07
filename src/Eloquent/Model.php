<?php
namespace Nodes\Database\Eloquent;

use Carbon\Carbon;

/**
 * Class Model
 *
 * @trait
 * @package Nodes\Database\Eloquent
 */
trait Model
{
    /**
     * Convert date to human-readable
     * E.g. "2 hours ago"
     *
     * @author Morten Rugaard <moru@nodes.dk>
     * @date   21-10-2015
     *
     * @access public
     * @param  string  $column
     * @param  string  $format
     * @param  integer $maxDays
     * @return string
     */
    public function getDateHumanReadable($column, $format = 'd-m-Y H:i:s', $maxDays = 3)
    {
        // Retrieve value of column
        $date = $this->{$column};

        // Make sure date is a Carbon object
        // otherwise just return untouched value
        if (!$date instanceof Carbon) {
            return $column;
        }

        // If date is older than $maxDays
        // we'll return the date and time
        $daysDifference = $date->diffInDays();
        if ($daysDifference > $maxDays) {
            return $date->format($format);
        }

        return $date->diffForHumans();
    }
}