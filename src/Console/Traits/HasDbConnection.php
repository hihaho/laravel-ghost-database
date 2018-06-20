<?php
namespace HiHaHo\GhostDatabase\Console\Traits;

use HiHaHo\GhostDatabase\Exceptions\DatabaseNotConfiguredException;
use Illuminate\Support\Arr;

trait HasDbConnection
{
    protected $connection;

    /**
     * @param $connection
     * @throws DatabaseNotConfiguredException
     */
    private function setDbConnection(string $connection): void
    {
        $connections = $this->laravel['config']['database.connections'];

        if (is_null($config = Arr::get($connections, $connection))) {
            throw new DatabaseNotConfiguredException("Ghost database [{$connection}] not configured.");
        }

        $this->connection = $connection;
    }
}
