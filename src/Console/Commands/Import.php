<?php
/**
 * Created by PhpStorm.
 * User: Robert Boes
 * Date: 16-06-2018
 * Time: 12:07
 */

namespace HiHaHo\GhostDatabase\Console\Commands;

use HiHaHo\GhostDatabase\Console\Traits\HasDbConnection;
use HiHaHo\GhostDatabase\Exceptions\DatabaseNotConfiguredException;
use HiHaHo\GhostDatabase\Exceptions\NoSnapshotsFoundException;
use Illuminate\Console\Command;
use Spatie\DbSnapshots\Snapshot;
use Symfony\Component\Console\Input\InputOption;

class Import extends Command
{
    use HasDbConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ghost-db:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the SQL file to the ghost database';

    /**
     * @var \HiHaHo\GhostDatabase\GhostDatabase
     */
    protected $ghostDatabase;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->ghostDatabase = app()->make('ghost-database');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws DatabaseNotConfiguredException
     * @throws NoSnapshotsFoundException
     */
    public function handle()
    {
        $this->setDbConnection(
            $this->input->getOption('database') ?: config('ghost-database.default_connection')
        );

        $native = $this->input->getOption('native-import') ?: config('ghost-database.use_native_importer');

        $snapshot = $this->import($native);

        $this->info("Snapshot `{$snapshot->name}` loaded!");
    }

    /**
     * @param bool $native
     * @return Snapshot
     * @throws NoSnapshotsFoundException
     */
    protected function import($native = false): Snapshot
    {
        if ($native) {
            $this->info("Starting native import");
            return $this->ghostDatabase->importNative($this->connection);
        }

        return $this->ghostDatabase->import($this->connection);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['native-import', null, InputOption::VALUE_NONE, 'Use the native importer'],
        ];
    }
}
