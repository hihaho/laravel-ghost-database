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
use Illuminate\Console\Command;
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
     */
    public function handle()
    {
        $this->setDbConnection(
            $this->input->getOption('database') ?: config('ghost-database.default_connection')
        );

        $snapshot = $this->ghostDatabase->import($this->connection);

        $this->info("Snapshot `{$snapshot->name}` loaded!");
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
        ];
    }
}
