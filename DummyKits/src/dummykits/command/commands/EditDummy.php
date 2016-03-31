<?php

namespace dummykits\command\commands;

use pocketmine\command\CommandSender;
use pocketmine\Player;

use dummykits\Main;
use dummykits\command\DummyCommand;

class EditDummy extends DummyCommand {
        
        public function __construct(Main $plugin) {
                parent::__construct($plugin, "editdummy", "Allow's editing of an existing Dummy", "/EditDummy", "changedummy");
                $this->setPermission("dummykits.command.edit");
        }
        
        public function onExecute(CommandSender $sender, array $args) {
                
        }
}
