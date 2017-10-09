<?php

namespace Etbusch\PostgresCtes;

use Etbusch\PostgresCtes\Grammars\PostgresCteGrammar as QueryGrammar;
use Illuminate\Database\PostgresConnection as BaseConnection;

class PostgresCteConnection extends BaseConnection
{

    /**
     * Get the default query grammar instance.
     *
     * @return \Illuminate\Database\Query\Grammars\PostgresGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }
}
