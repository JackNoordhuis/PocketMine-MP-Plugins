<?php

namespace fly;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;
use fly\EventListener;
use fly\FlySession;

class Main extends PluginBase {

        public $active = [];

        public function onEnable() {
                $this->getCommand("fly")->setExecutor(new FlyCommand($this));
                $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
                $this->getLogger()->info(TF::AQUA . "Fly v1.0" . TF::GREEN . " by " . TF::YELLOW . "Jack Noordhuis" . TF::GREEN . ", Loaded successfully!");
        }

        public function addFlyingSession(Player $player) {
                if($this->hasFlyingSession($player))
                        return null;
                $name = $player->getName();
                return $this->active[spl_object_hash($player)] = new FlySession($this, $player);
        }

        public function hasFlyingSession(Player $player) {
                $name = $player->getName();
                if(!isset($this->active[spl_object_hash($player)]))
                        return false;
                return $this->active[spl_object_hash($player)] instanceof FlySession;
        }

        public function getFlyingSession(Player $player) {
                if(!$this->hasFlyingSession($player))
                        return null;
                return $this->active[spl_object_hash($player)];
        }

        public function removeFlyingSession(Player $player) {
                if(!$this->hasFlyingSession($player))
                        return false;
                $this->getFlyingSession($player)->close();
                unset($this->active[spl_object_hash($player)]);
        }

}
