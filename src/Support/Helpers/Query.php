<?php

if (! function_exists('render_sql')) {
    /**
     * Render query SQL string.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return string
     */
    function render_sql(\Illuminate\Database\Query\Builder $query)
    {
        return vsprintf($query->toSql(), $query->getBindings());
    }
}
