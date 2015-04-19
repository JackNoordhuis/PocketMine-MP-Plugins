<?php
/*
 * WorldProtector plugin for PocketMine-MP
 * Copyright (C) 2014 Jack Noordhuis (CrazedMiner) 
 * <https://github.com/CrazedMiner/PocketMine-MP-Plugins/tree/master/WorldProtector>
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

namespace CrazedMiner;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerInteractEvent;

class Main extends PluginBase implements Listener {
  
    public function onLoad() {
        $this->getLogger()->info(TextFormat::YELLOW . "Loading WorldProtector Stats v1.0.0 by CrazedMiner....");
    }
  
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::GREEN . "WorldProtector v1.0.0 by CrazedMiner is now Enabled!");
    }
    
    public function onDisable() {
        $this->getLogger()->info(TextFormat::RED . "WorldProtector v1.0.0 by CrazedMiner is now Disabled!");
    }
    
    public function onBlockBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        if(!($player->haspermission("worldprotector.block.break"))) {
            $event->setCancelled(true);
        }else {
            $event->setCancelled(false);
        }
    }
    
    public function onBlockPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        if(!($player->haspermission("worldprotector.block.place"))) {
            $event->setCancelled(true);
        }else {
            $event->setCancelled(false);
        }
    }
    
    public function onBlockTouch(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        
        if(!($player->haspermission("worldprotetor.block.break"))) {
            $event->setCancelled(true);
        }else {
            $event->setCancelled(false);
        }
    }
}
