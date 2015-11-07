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

namespace positionteller\tasks;

use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat as TF;

use positionteller\Main;

class ShowPos extends PluginTask {
    
    private $plugin;

    public function __construct(Main $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }
    
    public function getPlugin() {
        return $this->plugin;
    }
    
    public function onRun($tick){
        foreach($this->getPlugin()->active as $player) {
            $player->sendPopup(str_replace(array("@x", "@y", "@z"), array(round($player->getX(), 1), round($player->getY(), 2), round($player->getY(), 1)), Main::translateColors($this->getPlugin()->getConfigValue("messages.showpos.format"))));
        }
    }
    
}