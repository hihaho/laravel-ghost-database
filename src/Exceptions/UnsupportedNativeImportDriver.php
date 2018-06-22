<?php

namespace HiHaHo\GhostDatabase\Exceptions;


class UnsupportedNativeImportDriver extends \Exception
{
    public static function forDriver(string $driver): self
    {
        return new static("Cannot native exporter for db driver `{$driver}`. Use `mysql`, `pgsql` or `sqlite`.");
    }
}
