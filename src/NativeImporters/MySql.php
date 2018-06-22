<?php

namespace HiHaHo\GhostDatabase\NativeImporters;


use HiHaHo\GhostDatabase\NativeDbImport;

class MySql extends NativeDbImport
{
    /** @var int */
    protected $port = 3306;

    public function getImportCommand(): string
    {
        $command[] = 'mysql';

        if ($this->userName) {
            $command[] = "-u {$this->userName}";
        }

        if ($this->userName && $this->password) {
            $command[] = "-p{$this->password}";
        }

        if ($this->host) {
            $command[] = "-h {$this->host}";
        }

        $command[] = "{$this->dbName}";

        $command[] = "< {$this->snapshot->disk->path($this->snapshot->fileName)}";

        return implode(' ', $command);
    }
}
