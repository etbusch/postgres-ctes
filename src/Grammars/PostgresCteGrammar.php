<?php

namespace Etbusch\PostgresCtes\Grammars;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\PostgresGrammar as BasePostgresGrammar;

class PostgresCteGrammar extends BasePostgresGrammar
{

     /**
     * The components that make up a select clause.
     *
     * @var array
     */
    protected $selectComponents = [
        'with',
        'aggregate',
        'columns',
        'from',
        'joins',
        'wheres',
        'groups',
        'havings',
        'orders',
        'limit',
        'offset',
        'unions',
        'lock',
    ];

    /**
     * Prepends the Query statement with the CTE "with" statement if it exists.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string
     * @return string
     */
    protected function compileWith(Builder $query, $with)
    {
        if (! empty($with)) {
            return 'with '.join(', ',$with)." ";
        }

        return '';
    }


    /**
     * Compile the components necessary for a select clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return array
     */
    protected function compileComponents(Builder $query)
    {
        $sql = [];

        foreach ($this->selectComponents as $component) {
            // To compile the query, we'll spin through each component of the query and
            // see if that component exists. If it does we'll just call the compiler
            // function for the component which is responsible for making the SQL.
            if (isset($query->$component) && ! is_null($query->$component)) {
                $method = 'compile'.ucfirst($component);

                $sql[$component] = $this->$method($query, $query->$component);
            }
        }

        return $sql;
    }
}