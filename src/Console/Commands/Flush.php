<?php

namespace HiHaHo\GhostDatabase\Console\Commands;


use HiHaHo\GhostDatabase\Console\Traits\HasDbConnection;
use HiHaHo\GhostDatabase\Exceptions\DatabaseNotConfiguredException;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;

class Flush extends Command
{
    use ConfirmableTrait, HasDbConnection;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ghost-db:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flushes the ghost database';

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

        if (! $this->confirmToProceed("Application in production, are you sure you want to flush the ghost database?")) {
            return;
        }

        $this->dropAllTables();

        $this->info("Flushed ghost database `{$this->connection}` successfully.");
    }

    /**
     * Drop all of the database tables.
     *
     * @return void
     */
    protected function dropAllTables()
    {
        $this->ghostDatabase->flush($this->connection);
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
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
        ];
    }
}
