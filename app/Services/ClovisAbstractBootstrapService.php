<?php

namespace App\Services;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Cache\RedisCache;

abstract class ClovisAbstractBootstrapService
{
    public $botman;

    public function __construct($driver, $config)
    {
        // Load the driver(s) you want to use
        DriverManager::loadDriver($driver);

        // Create an instance
        $this->botman = BotManFactory::create(
            $config,
            new RedisCache(
                env('APP_REDIS_HOST'),
                env('APP_REDIS_PORT'),
                env('APP_REDIS_PASSWORD')
            )
        );
    }

    public function run()
    {
        $this->flow();
        $this->botman->listen();
    }

    protected function greattingsText()
    {
        $greattings = [
            'oi', 'olá', 'ola', 'e ai', 'e aí', 'fala ae', 'hi', 'hello', 'olá, clovis'
        ];
        return '(' . implode("|", $greattings) . ').*';
    }

    protected function negationsText()
    {
        $negations = [
            'nao', 'não', 'negativo', 'nem pensar', 'não pode', 'não to afim', 'não enche'
        ];
        return '(' . implode("|", $negations) . ').*';

    }

    protected function confirmationsText()
    {
        $negations = [
            'sim', 'pode', 'claro', 'claro que sim', 'positivo', 'quero'
        ];
        return '(' . implode("|", $negations) . ')';

    }


    abstract public function flow();
}
