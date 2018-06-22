<?php

namespace HiHaHo\GhostDatabase\Exceptions;


use Symfony\Component\Process\Process;

class DbImportFailed extends \Exception
{
    /**
     * @param \Symfony\Component\Process\Process $process
     *
     * @return \HiHaHo\GhostDatabase\Exceptions\DbImportFailed
     */
    public static function processDidNotEndSuccessfully(Process $process)
    {
        return new static("The dump process failed with exitcode {$process->getExitCode()} : {$process->getExitCodeText()} : {$process->getErrorOutput()}");
    }
}