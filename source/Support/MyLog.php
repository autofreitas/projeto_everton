<?php


namespace Source\Support;


use DateTimeZone;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\SendGridHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Logger;
use Source\Core\HandlerLogDatabase;
use Source\Models\User\User;

class MyLog
{
    private $log;
    private $client;
    private $extra;

    public function __construct(string $name, ?User $client)
    {
        $this->client = $client;
        $this->log = new Logger($name);
        $this->log->setTimezone(new DateTimeZone('America/Sao_Paulo'));
        $this
          //  ->handlerBrowser()
            ->handlerDatabase()
          //  ->handlerEmail()
            ->handlerFile()
            ->handlerTelegram();
    }

    private function handlerDatabase(): MyLog
    {
        $this->log->pushHandler(
            new HandlerLogDatabase(Logger::CRITICAL)
        );
        return $this;
    }

    private function handlerFile(): MyLog
    {
        $this->log->pushHandler(
            new StreamHandler(CONF_LOG_DIR,Logger::WARNING)
        );
        return $this;
    }

    private function handlerBrowser(): MyLog
    {
        $this->log->pushHandler(
            new BrowserConsoleHandler(Logger::DEBUG)
        );
        return $this;
    }

    private function handlerEmail(): MyLog
    {
        $this->log->pushHandler(
            new SendGridHandler(
                CONF_MAIL_USER,
                CONF_MAIL_PASS,
                "noreply@automacaonaveia.com.br",
                "carlos@autofreitas.com.br",
                "LOG em automacaonaveia.cemf.com.br".date("d/m/Y H:i:s"),
                Logger::CRITICAL)
        );
        return $this;
    }
    private function handlerTelegram(): MyLog
    {
        $tele_handler = new TelegramBotHandler(
            CONF_SOCIAL_TELEGRAM_BOT_KEY,
            CONF_SOCIAL_TELEGRAM_BOT_CHANNEL,
            Logger::ALERT);
        $tele_handler->setFormatter(new LineFormatter("%level_name%: %message%"));
        $this->log->pushHandler(
        $tele_handler
        );
        return $this;
    }

    private function context(): array
    {
        $params = [
            'id' => $this->client->id ?? null,
            'name' => $this->client->first_name ?? null,
            'ip'=> $_SERVER['REMOTE_ADDR']
        ];
        return $params;
    }

    public function extra(array $extra = []): MyLog
    {
        $this->extra = $extra;
        $this->log->pushProcessor(function ($record){
            $record['extra'] = $this->extra;
            return $record;
        });
        return $this;
    }

    public function debug($message,array $context = []): void
    {

        $this->log->debug($message, empty($context) ? $this->context() : $context);
    }

    public function info($message, array $context = []): void
    {
        $this->log->info($message, empty($context) ? $this->context() : $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->log->notice($message, empty($context) ? $this->context() : $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->log->warning($message, empty($context) ? $this->context() : $context);
    }

    public function error($message, array $context = []): void
    {
        $this->log->error($message, empty($context) ? $this->context() : $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->log->critical($message, empty($context) ? $this->context() : $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->log->alert($message, empty($context) ? $this->context() : $context);
    }

    public function emergency($message, array $context = []): void
    {
        $this->log->emergency($message, empty($context) ? $this->context() : $context);
    }


}