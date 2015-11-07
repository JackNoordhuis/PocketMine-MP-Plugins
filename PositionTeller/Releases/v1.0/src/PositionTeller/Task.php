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

use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat as TF;

use PositionTeller\Main;

class Task extends PluginTask {
    
    protected $plugin;

    public function __construct(Main $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
        $this->current = 0;
    }
    
    public function onRun($tick){
        foreach($this->plugin->active as $player) {
            $player->sendPopup(TF::GOLD . "X: " . TF::AQUA . round($player->getX(), 2) . TF::GOLD . " Y: " . TF::AQUA . round($player->getY(), 2) . TF::GOLD . " Z: " . TF::AQUA . round($player->getZ(), 2));
        }
    }
    
}