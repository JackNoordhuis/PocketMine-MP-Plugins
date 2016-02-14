<?php

namespace inventoryclear\session;

use pocketmine\Player;
use pocketmine\inventory\PlayerInventory;

class ViewInv {
        
        public $owner = null;
        
        public $target = null;
        
        public $lastKnownInv = null;
        
        public function __construct(Player $owner, Player $target, $open = false) {
                $this->owner = $owner;
                $this->target = $target;
                if($open) {
                        $this->open();
                }
        }
        
        public function open() {
                $this->lastKnownInv = clone $this->owner->getInventory();
                $this->owner->getInventory()->setArmorContents($this->target->getInventory()->getArmorContents());
                $this->owner->getInventory()->sendArmorContents($this->owner);
                $this->owner->getInventory()->setContents($this->target->getInventory()->getContents());
                $this->owner->getInventory()->sendContents($this->owner);
        }
        
        public function close() {
                var_dump($this->lastKnownInv);
                if($this->lastKnownInv != null) {
                        $this->owner->getInventory()->clearAll();
                        $this->owner->getInventory()->setArmorContents($this->lastKnownInv->getArmorContents());
                        $this->owner->getInventory()->sendArmorContents($this->owner);
                        $this->owner->getInventory()->setContents($this->lastKnownInv->getContents());
                        $this->owner->getInventory()->sendContents($this->owner);
                        $this->lastKnownInv = null;
                        var_dump($this->lastKnownInv);
                }
        }
        
        public function end() {
                $this->close();
                unset($this->owner);
                unset($this->target);
                unset($this->lastKnownInv);
        }
        
        public function __destruct() {
                $this->end();
        }
        
}
