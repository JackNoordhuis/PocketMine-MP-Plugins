<?php

/*
 * PvP-Stats plugin for PocketMine-MP
 * Copyright (C) 2014 Jack Noordhuis (CrazedMiner) <https://github.com/CrazedMiner/PocketMine-MP-Plugins/tree/master/PvP-Stats>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/


namespace CrazedMiner\PvPStats;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\event\player\PlayerDeathEvent

class Main extends PluginBase implements Listener {
  
  public function onLoad(){
    $this->getLogger()->info(TextFormat::YELLOW . "Loading PvP Stats v1.0.0 by CrazedMiner....");
    }
  
    public function onEnable(){
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->getLogger()->info(TextFormat::GREEN . "PvP Stats v1.0.0 by CrazedMiner is now Enabled!");
    }
    
    public function onDisable(){
      $this->getLogger()->info(TextFormat::RED . "PvP Stats v1.0.0 by CrazedMiner is now Didabled!");
      }
    
    public function onPlayerDeath(PlayerDeathEvent $event){
      $victim = $event->getEntity();
      if($victim instanceof Player){
          
        }
      }
    
}