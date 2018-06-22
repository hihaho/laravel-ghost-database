<?php

namespace HiHaHo\GhostDatabase;


use HiHaHo\GhostDatabase\Exceptions\DatabaseNotConfiguredException;
use HiHaHo\GhostDatabase\Exceptions\UnsupportedNativeImportDriver;
use HiHaHo\GhostDatabase\NativeImporters\MySql;
use HiHaHo\GhostDatabase\NativeImporters\PostgreSql;
use HiHaHo\GhostDatabase\NativeImporters\Sqlite;
use Spatie\DbSnapshots\Snapshot;

class NativeDbImportFactory
{
    public static function createForConnection(string $connectionName)
    {
        $dbConfig = config("database.connections.{$connectionName}");

        if (is_null($dbConfig)) {
            throw new DatabaseNotConfiguredException();
        }

        $dbExporter = static::forDriver($dbConfig['driver'])
            ->setHost($dbConfig['host'] ?? '')
            ->setDbName($dbConfig['database'])
            ->setUserName($dbConfig['username'] ?? '')
            ->setPassword($dbConfig['password'] ?? '');

        if (isset($dbConfig['port'])) {
            $dbExporter->setPort($dbConfig['port']);
        }

        return $dbExporter;
    }

    /**
     * @param $dbDriver
     * @return NativeDbImport
     * @throws UnsupportedNativeImportDriver
     */
    protected static function forDriver(string $dbDriver): NativeDbImport
    {
        $driver = strtolower($dbDriver);

        if ($driver === 'mysql') {
            return new MySql();
        }

        if ($driver === 'pgsql') {
            return new PostgreSql();
        }

        if ($driver === 'sqlite') {
            return new Sqlite();
        }

        throw UnsupportedNativeImportDriver::forDriver($driver);
    }

    protected function importFromSnapshot(Snapshot $snapshot)
    {

    }
}