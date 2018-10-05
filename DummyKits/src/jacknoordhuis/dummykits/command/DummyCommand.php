<?php

namespace jacknoordhuis\dummykits\command;

use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\CommandSender;

use jacknoordhuis\dummykits\Main;

abstract class DummyCommand extends Command implements PluginIdentifiableCommand {
        
        private $plugin;

        public function __construct(Main $plugin, $name, $description, $usage, ...$aliases) {
                parent::__construct($name, $description, $usage, $aliases);
                $this->plugin = $plugin;
        }
        
        public function getPlugin() {
                return $this->plugin;
        }
        
        public function execute(CommandSender $sender, $commandLabel, string $args) {
                if($this->testPermission($sender)) {
                        $result = $this->onExecute($sender, $args);
                        if(is_string($result)) {
                                $sender->sendMessage($result);
                        }
                        return true;
                }
                return false;
        }
        
        abstract public function onExecute(CommandSender $sender, array $args);
}
