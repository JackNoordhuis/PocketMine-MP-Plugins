<?php

namespace jacknoordhuis\dummykits\kit;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\entity\Effect;

class Kit {
        
        private $name = "";
        
        private $armor = [];
        
        private $items = [];
        
        private $effects = [];
        
        private $clearInv = true;
        
        private $clearEffects = true;
        
        public function __construct($name, array $armor, array $items, array $effects, $clearInv, $clearEffects) {
                $this->name = $name;
                $this->armor = $armor;
                $this->items = $items;
                $this->effects = $effects;
                $this->clearInv = $clearInv;
                $this->clearEffects = $clearEffects;
        }
        
        public function getName() {
                return $this->name;
        }
        
        public function getArmor() {
                return $this->armor;
        }
        
        public function addItem(Item $item) {
                $this->items[] = $item;
        }
        
        public function getItems() {
                return $this->items;
        }
        
        public function addEffect(Effect $effect) {
                $this->effects[] = $effect;
        }
        
        public function getEffects() {
                return $this->effects;
        }
        
        public function getClearInv() {
                return $this->clearInv;
        }
        
        public function getClearEffects() {
                return $this->clearEffects;
        }
}
