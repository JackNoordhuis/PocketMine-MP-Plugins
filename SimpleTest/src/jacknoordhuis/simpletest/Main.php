<?php

namespace jacknoordhuis\simpletest;

use pocketmine\plugin\PluginBase;

use simpletest\EventListener;

class Main extends PluginBase {
        
        public function onEnable() {
                $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        }
        
}
