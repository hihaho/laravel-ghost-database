<?php

namespace HiHaHo\GhostDatabase;

use Carbon\Carbon;
use HiHaHo\GhostDatabase\Exceptions\NoSnapshotsFoundException;
use Illuminate\Contracts\Filesystem\Factory;
use Spatie\DbSnapshots\Snapshot;
use Spatie\DbSnapshots\SnapshotFactory;
use Spatie\DbSnapshots\SnapshotRepository;

class GhostDatabase
{
    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $laravel;

    /**
     * @var \Spatie\DbSnapshots\SnapshotRepository
     */
    protected $snapshotRepository;

    public function __construct(\Illuminate\Contracts\Foundation\Application $application)
    {
        $this->laravel = $application;
    }

    /**
     * Creates and sets the SnapshotRepository
     *
     * @param string|null $diskName
     * @return SnapshotRepository
     */
    protected function setSnapshotRepository(string $diskName = null): SnapshotRepository
    {
        if (!isset($diskName)) {
            $diskName = config('ghost-database.disk');
        }

        $disk = app(Factory::class)->disk($diskName);

        return $this->snapshotRepository = new SnapshotRepository($disk);
    }

    /**
     * Returns the last created snapshot, will throw an error if no snapshots are found
     *
     * @return Snapshot
     * @throws NoSnapshotsFoundException
     */
    protected function getLatestSnapshot(): Snapshot
    {
        $snapshots = $this->snapshotRepository->getAll();
        if ($snapshots->isEmpty()) {
            throw new NoSnapshotsFoundException();
        }

        return $snapshots->first();
    }

    /**
     * Exports the `$connection` database to a selected storage disk
     *
     * @param string $connection The database connection
     * @param string|null $name Name of the to be exported file
     * @param string|null $disk Selected storage disk, defaults to the one configured in ghost-database.disk
     * @return Snapshot
     */
    public function export(string $connection, string $name = null, string $disk = null): Snapshot
    {
        if (!isset($name)) {
            $name = $connection . '_' . Carbon::now()->format('Ydm');
        }

        if (!isset($disk)) {
            $disk = config('ghost-database.disk');
        }

        return app(SnapshotFactory::class)
            ->create($name, $disk, $connection);
    }

    /**
     * Imports the latest Snapshot in the `$connection` database
     *
     * @param string $connection The database connection
     * @param Snapshot|null $snapshot
     * @param string|null $diskName
     * @return Snapshot
     * @throws NoSnapshotsFoundException
     */
    public function import(string $connection, Snapshot $snapshot = null, string $diskName = null): Snapshot
    {
        if (!isset($snapshot)) {
            $this->setSnapshotRepository($diskName);

            $snapshot = $this->getLatestSnapshot();
        }

        $snapshot->load($connection);

        return $snapshot;
    }

    /**
     * Flushes the selected database
     *
     * @param string $connection The database connection
     */
    public function flush(string $connection): void
    {
        $this->laravel['db']->connection($connection)
            ->getSchemaBuilder()
            ->dropAllTables();
    }

    /**
     * Deletes all snapshots from the default disk or `$diskName`
     *
     * @param string|null $diskName The disk name
     * @throws NoSnapshotsFoundException
     */
    public function cleanupSnapshots(string $diskName = null)
    {
        $this->setSnapshotRepository($diskName);

        $snapshots = $this->snapshotRepository->getAll();
        if ($snapshots->isEmpty()) {
            throw new NoSnapshotsFoundException();
        }

        $snapshots->each(function(Snapshot $snapshot) {
            $snapshot->delete();
        });
    }
}
