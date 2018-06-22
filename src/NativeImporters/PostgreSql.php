<?php

namespace HiHaHo\GhostDatabase\NativeImporters;

use HiHaHo\GhostDatabase\NativeDbImport;

class PostgreSql extends NativeDbImport
{
    protected $port = 5432;

    public function getImportCommand(): string
    {
        $command[] = 'psql';

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

