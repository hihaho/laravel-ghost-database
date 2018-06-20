<?php
/**
 * Created by PhpStorm.
 * User: Robert Boes
 * Date: 20-06-2018
 * Time: 14:10
 */

namespace HiHaHo\GhostDatabase\Console\Commands;


use HiHaHo\GhostDatabase\Exceptions\NoSnapshotsFoundException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class CleanupSnapshots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ghost-db:cleanup-snapshots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all current snapshots';

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
     * @throws NoSnapshotsFoundException
     */
    public function handle()
    {
        $disk =  $this->input->getOption('disk')
            ?: config('ghost-database.disk');

        try {
            $this->ghostDatabase->cleanupSnapshots($disk);
        }
        catch (NoSnapshotsFoundException $exception) {
            if (!$this->input->getOption('silent')) {
                throw $exception;
            }
        }

        $this->info("Exports from disk `{$disk}` deleted.");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['disk', null, InputOption::VALUE_OPTIONAL, 'The disk connection to use.'],
            ['silent', 's', InputOption::VALUE_NONE, 'Don\'t throw an exception when there are no snapshots'],
        ];
    }
}
