<?php

namespace jacknoordhuis\fly;

use pocketmine\Player;

class FlySession {

        private $plugin;
        private $player;
        private $flying = false;

        public function __construct(Main $plugin, Player $player) {
                $this->plugin = $plugin;
                $this->player = $player;
        }

        public function getFlying() {
                return $this->flying;
        }

        public function setFlying($value = true) {
                $this->flying = $value;
                $this->updateFly();
        }

        public function updateFly() {
                $this->player->resetFallDistance();
                $this->player->setAllowFlight($this->flying);
        }

        public function close() {
                unset($this->plugin);
                unset($this->player);
                unset($this->flying);
        }

}
