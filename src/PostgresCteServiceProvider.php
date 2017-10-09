<?php

namespace Etbusch\PostgresCtes;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;
use Illuminate\Database\Builder;
use Etbusch\PostgresCtes\PostgresCteConnection;

class PostgresCtesServiceProvider extends ServiceProvider
{
    /**
     * Register the application services and bind the pgsql driver to our custom connection
     *
     * @return void
     */
    public function register()
    {
        Connection::resolverFor('pgsql', function ($connection, $database, $prefix, $config) {
            return new PostgresCteConnection($connection, $database, $prefix, $config);
        });

        //Builder::bindings["with"] = [];

        Builder::macro('cte', function ($as, $query) {

            // If the given query is a Closure, we will execute it while passing in a new
            // query instance to the Closure. This will give the developer a chance to
            // format and work with the query before we cast it to a raw SQL string.
            if ($query instanceof Closure) {
                $callback = $query;
                $callback($query = $this->newQuery());
            }

            // Here, we will parse this query into an SQL string and an array of bindings
            // so we can add it to the query builder using the selectRaw method so the
            // query is included in the real SQL generated by this builder instance.
            if ($query instanceof self) {
                $query = $query->toSql();
                $bindings = $query->getBindings();
            } elseif (is_string($query)) {
                $bindings = [];
            } else {
                throw new InvalidArgumentException;
            }

            $this->with[] = $this->grammar->wrap($as).' as ('.$query.')';
            if ($bindings) {
                $this->addBinding($bindings, 'with');
            }
            return $this;
        });
    }
}