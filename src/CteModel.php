<?php

namespace Etbusch\PostgresCtes;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Etbusch\PostgresCtes\CteBuilder as QueryBuilder;

abstract class CteModel extends BaseModel
{

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();

        return new QueryBuilder(
            $connection, $connection->getQueryGrammar(), $connection->getPostProcessor()
        );
    }
}