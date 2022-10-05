<?php


namespace Source\Core;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Source\Models\Log;

class HandlerLogDatabase extends AbstractProcessingHandler
{

    private $log;

    public function __construct($level = Logger::WARNING, bool $bubble = true)
    {
        $this->log = new Log();
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $this->log->boostrap(
            $record["message"],
            $record["level"],
            $record["level_name"],
            json_encode($record["context"]),
            $record["channel"],
            json_encode($record["extra"])
        )->save();

    }
}