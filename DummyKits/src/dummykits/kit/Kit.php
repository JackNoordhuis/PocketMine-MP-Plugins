<?php

namespace dummtkit\kit;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\entity\Effect;

class Kit {
        
        private $name = "";
        
        private $items = [];
        
        private $effects = [];
        
        public function __construct($name, array $items, array $effects) {
                $this->name = $name;
                $this->items = $items;
                $this->effects = $effects;
        }
        
        public function getName() {
                return $this->name;
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
}
