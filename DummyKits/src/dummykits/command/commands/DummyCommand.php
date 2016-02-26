<?php

namespace dummykits\command\commands;

use pocketmine\command\Command;

abstract class DummyCommand extends Command {
        
        public function __construct($name, $description = "", $usageMessage = null, string $aliases = array()) {
                parent::__construct($name, $description, $usageMessage, $aliases);
        }
}
