<?php

/*
 * PositionTeller plugin for PocketMine-MP
 * Copyright (C) 2014 Jack Noordhuis (CrazedMiner) 
 * <https://github.com/CrazedMiner/PocketMine-MP-Plugins/tree/master/PositionTeller>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

namespace PositionTeller;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use pocketmine\Player;

class Main extends PluginBase {
    
    public $active = array();

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getCommand("togglepos")->setExecutor(new TogglePosCommand($this));
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new Task($this), 4);
        $this->getLogger()->info(TF::AQUA . "PositionTeller v1.0" . TF::GREEN . " by " . TF::YELLOW . "Jack Noordhuis" . TF::GREEN . ", Loaded successfully!");
    }

    public function onDisable() {
        unset($this->active);
    }
    
    public function addActive(Player $player) {
        $this->active[spl_object_hash($player)] = $player;
    }
    
    public function isActive(Player $player) {
        return isset($this->active[spl_object_hash($player)]);
    }
    
    public function removeActive(Player $player) {
        unset($this->active[spl_object_hash($player)]);
    }

}
