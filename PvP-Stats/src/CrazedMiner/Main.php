<?php

/*
 * PvP-Stats plugin for PocketMine-MP
 * Copyright (C) 2014 Jack Noordhuis (CrazedMiner) 
 * <https://github.com/CrazedMiner/PocketMine-MP-Plugins/tree/master/PvP-Stats>
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


namespace \CrazedMiner;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\utils\Config;


class Main extends PluginBase implements Listener {
  
  public function onLoad(){
      $this->getLogger()->info(TextFormat::YELLOW . "Loading PvP Stats v1.0.0 by CrazedMiner....");
  }
  
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::GREEN . "PvP Stats v1.0.0 by CrazedMiner is now Enabled!");
    }
    
    public function onDisable(){
        $this->getLogger()->info(TextFormat::RED . "PvP Stats v1.0.0 by CrazedMiner is now Disabled!");
    }
    
    public function onPlayerDeath(PlayerDeathEvent $event){
        $v = $event->getEntity();
            if($v instanceof Player){
                $vd = new Config($this->getDataFolder() . "data/" . strtolower($v->getPlayer()->getName()) . ".txt", Config::ENUM);
                    if($vd->exists("Kills") && $vd->exists("Deaths"){
                        $vd->set("Deaths", $vd->get("Deaths") +1);
                        $vd->save();
                    }else{
                        $vd->setAll(array("Kills" =>0, "Deaths" =>1));
                        $vd->save();
                    }
                $c = event->getEntity()->getLastDamageCause()->getCause();
                if($c == 1){
                    $k = $event->getEntity()->getLastDamageCause()->getDamager();
                        if($k instanceof Player){
                            $kd = new Config($this->getDataFolder() . "data/" . strtolower($k->getPlayer()->getName()) . ".txt", Config::ENUM);
                                if($kd->exists("Kills") && $kd->exists("Deaths")){
                                    $kd->set("Kills", $kd->get("Kills") +1);
                                    $kd->save();
                        }else{
                            $kd->setAll(array("Kills" => 1, "Deaths" => 0));
                            $kd->save();
                        }
               }
           }
        }
     }
    
}
