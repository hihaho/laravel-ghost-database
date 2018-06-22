<?php

namespace HiHaHo\GhostDatabase;


use HiHaHo\GhostDatabase\Exceptions\DbImportFailed;
use Spatie\DbSnapshots\Snapshot;
use Symfony\Component\Process\Process;

abstract class NativeDbImport
{
    /** @var string */
    protected $host;

    /** @var string */
    protected $dbName;

    /** @var string */
    protected $userName;

    /** @var string */
    protected $password;

    /** @var int */
    protected $port = 5432;

    /** @var Snapshot */
    protected $snapshot;

    public function setUserName(string $userName)
    {
        $this->userName = $userName;

        return $this;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function setHost(string $host)
    {
        $this->host = $host;

        return $this;
    }

    public function setDbName(string $dbName)
    {
        $this->dbName = $dbName;

        return $this;
    }

    public function setPort(int $port)
    {
        $this->port = $port;

        return $this;
    }

    public function setSnapshot(Snapshot $snapshot)
    {
        $this->snapshot = $snapshot;

        return $this;
    }

    public function getSnapshot(): Snapshot
    {
        return $this->snapshot;
    }

    public abstract function getImportCommand(): string;

    public function execute()
    {
        $command = $this->getImportCommand();

        $process = new Process($command);

        $process->run();

        $this->checkIfImportWasSuccessFul($process);
    }

    protected function checkIfImportWasSuccessFul(Process $process)
    {
        if (! $process->isSuccessful()) {
            throw DbImportFailed::processDidNotEndSuccessfully($process);
        }
    }
}
