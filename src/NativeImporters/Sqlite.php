<?php

namespace HiHaHo\GhostDatabase\NativeImporters;


use HiHaHo\GhostDatabase\NativeDbImport;

class Sqlite extends NativeDbImport
{

    public function getImportCommand(): string
    {
        return "sqlite3 {$this->dbName} < {$this->snapshot->disk->path($this->snapshot->fileName)}";
    }
}
