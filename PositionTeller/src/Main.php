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
        $this->initTask();
        $this->getLogger()->info(TF::AQUA . "PositionTeller" . TF::GREEN . " by " . TF::YELLOW . "Jack Noordhuis" . TF::GREEN . ", Loaded successfully!");
    }

    public function onDisable() {
        
    }
    
    public function initTask() {
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new Task($this), 4);
    }
    
    public function addActive(Player $player) {
        $this->active[$player->getName()] = $player->getName();
    }
    
    public function isActive(Player $player) {
        return in_array($player->getName(), $this->active);
    }
    
    public function removeActive(Player $player) {
        unset($this->active[$player->getName()]);
    }

}
