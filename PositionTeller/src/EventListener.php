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

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;

use PositionTeller\Main;

class EventListener implements Listener {
    
    public function __construct(Main $main) {
        $this->plugin = $main;
    }
    
    public function onDeath(PlayerDeathEvent $event) {
        $player = $event->getEntity();
        if($this->plugin->isActive($player)) {
            $this->plugin->removeActive($player);
        }
    }
    
    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        if($this->plugin->isActive($player)) {
            $this->plugin->removeActive($player);
        }
    }
}

