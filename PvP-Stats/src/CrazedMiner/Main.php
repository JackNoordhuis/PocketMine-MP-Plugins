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

namespace CrazedMiner;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\IPlayer;

use CrazedMiner\StatsCommand;
use CrazedMiner\Providers\ProviderInterface;
use CrazedMiner\Providers\YAMLProvider;
use CrazedMiner\Providers\MYSQLProvider;

class Main extends PluginBase {
    
    protected $provider;


    public function onEnable() {
        $this->getCommand("stats")->setExecutor(new StatsCommand($this));
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->saveResource("Settings.yml");
        $this->setProvider();
        $this->getLogger()->info(TextFormat::GREEN . "PvP-Stats v1.0 by CrazedMiner is now Enabled!");
    }
    
    public function onDisable() {
        $this->getLogger()->info(TextFormat::RED . "PvP Stats v1.0.0 by CrazedMiner is now Disabled!");
    }
    
    public function setProvider() {
        $provider = (new Config($this->getDataFolder() . "Settings.yml"))->getAll()["data-provider"];
        unset($this->provider);
        
        switch(strtolower($provider)) {
            case "yaml":
                $provider = new YAMLProvider($this);
                break;
            case "mysql":
                $provider = new MYSQLProvider($this);
                break;
        }
        
        if(!isset($this->provider) or !($this->provider instanceof ProviderInterface)) {
            $this->provider = $provider;
        }else {
            $this->getLogger()->critical("Data Provider error!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }
    
    public function getProvider() {
        return $this->provider;
    }
    
    public function getPlayer(IPlayer $player) {
        return $this->provider->getPlayer($player);
    }
    
    public function getData($player) {
        return $this->provider->getData($player);
    }
    
    public function playerExists(IPlayer $player) {
        return $this->provider->playerExists($player);
    }
    
    public function addPlayer(IPlayer $player) {
        return $this->provider->addPlayer($player);
    }
    
    public function removePlayer(IPlayer $player) {
        return $this->provider->removePlayer($player);
    }
    
    public function updatePlayer(IPlayer $player, $type) {
        return $this->provider->updatePlayer($player, $type);
    }
    
}
