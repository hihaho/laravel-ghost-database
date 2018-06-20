<?php

namespace HiHaHo\GhostDatabase\Console\Commands;

use Carbon\Carbon;
use HiHaHo\GhostDatabase\Console\Traits\HasDbConnection;
use HiHaHo\GhostDatabase\Exceptions\DatabaseNotConfiguredException;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Spatie\DbSnapshots\Helpers\Format;
use Spatie\DbSnapshots\SnapshotFactory;
use Symfony\Component\Console\Input\InputOption;

class Export extends Command
{
    use HasDbConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ghost-db:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exports the selected database to a SQL file';

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
        $database =  $this->input->getOption('database')
            ?: config('ghost-database.export_connection')
            ?? config('database.default');

        $this->setDbConnection($database);

        $snapshot = $this->ghostDatabase->export($this->connection);

        $size = Format::humanReadableSize($snapshot->size());

        $this->info("Export of `{$this->connection}` created (size: {$size})");
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
