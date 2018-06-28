<?php
namespace App\Services;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

abstract class ClovisAbstractBootstrapService {

    public $botman;

    public function __construct($driver, $config) {
        // Load the driver(s) you want to use
        DriverManager::loadDriver($driver);

        // Create an instance
        $this->botman = BotManFactory::create($config);
    }

    public function run() {
        $this->flow();
        $this->botman->listen();
    }

    public abstract function flow();

}
