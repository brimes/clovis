<?php
namespace App\Services;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Cache\DoctrineCache;

abstract class ClovisAbstractBootstrapService {

    public $botman;

    public function __construct($driver, $config) {
        // Load the driver(s) you want to use
        DriverManager::loadDriver($driver);

        // Create an instance
        $this->botman = BotManFactory::create($config, new DoctrineCache($doctrineCacheDriver));
    }

    public function run() {
        $this->flow();
        $this->botman->listen();
    }

    public abstract function flow();

}
