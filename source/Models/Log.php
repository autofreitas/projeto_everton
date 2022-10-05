<?php


namespace Source\Models;


use Source\Core\Model;

class Log extends Model
{
    public function __construct()
    {
        parent::__construct("log", ["id"], ["description", "level", "context", "channel"]);
    }

    public function boostrap(
        string $message,
        int $level,
        string $level_name,
        string $context,
        string $channel,
        string $extra = ""
    ): Log {

        $this->description = $message;
        $this->level = $level;
        $this->level_name = $level_name;
        $this->context = $context;
        $this->channel = $channel;
        $this->extra = $extra;
        return $this;

    }

    public function save(): bool
    {

        /*Verifica os campos obrigatórios*/

        if (!$this->required()) {
            return false;
        }
        $this->create((array)$this->data());
        if ($this->fail()) {
            $this->message->error("Erro ao cadastrar LOG")->render();
            return false;
        }
        return true;
    }

}