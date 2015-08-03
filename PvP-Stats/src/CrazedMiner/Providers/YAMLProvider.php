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

namespace CrazedMiner\Providers;

use pocketmine\IPlayer;
use pocketmine\utils\Config;

use CrazedMiner\Main;

class YAMLProvider implements ProviderInterface {
    
    protected $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        @mkdir($this->plugin->getDataFolder() . "players/");
        $this->plugin->getLogger()->info($this->plugin->translateColors("&r&aData Provider set to &r&6YAML&r&a!"));
    }
    
    public function getPlayer(IPlayer $player) {
        $name = strtolower($player->getName());
        if($this->playerExists($player)) {
            return (new Config($this->plugin->getDataFolder() . "players/" . $name . ".yml", Config::YAML))->getAll();
        }
        return null;
    }
    
    public function getData($player) {
        $name = strtolower($player);
        if(file_exists($this->plugin->getDataFolder() . "players/" . $name . ".yml")) {
            return (new Config($this->plugin->getDataFolder() . "players/" . $name . ".yml", Config::YAML))->getAll();
        }
        return null;
    }
    
    public function playerExists(IPlayer $player) {
        $name = strtolower($player->getName());
        return file_exists($this->plugin->getDataFolder() . "players/" . $name . ".yml");
    }
    
    public function addPlayer(IPlayer $player) {
        $name = strtolower($player->getName());
            return new Config($this->plugin->getDataFolder() . "players/" . $name . ".yml", Config::YAML, array(
                "name" => $name,
                "kills" => 0,
                "deaths" => 0
            ));
    }
    
    public function removePlayer(IPlayer $player) {
        $name = strtolower($player->getName());
        if($this->playerExists($player)) {
            @unlink($this->plugin->getDataFolder() . "players/" . $name . ".yml");
        }else {
            return null;
        }
    }
    
    public function updatePlayer(IPlayer $player, $type) {
        $name = strtolower($player->getName());
        if($this->playerExists($player)) {
            @mkdir($this->plugin->getDataFolder() . "players/" . $name . ".yml");
            $data = new Config($this->plugin->getDataFolder() . "players/" . $name . ".yml");
            $data->set($type, $data->getAll()[$type] + 1);
            return $data->save();
        }else {
            $this->addPlayer($player);
        }
    }
    
    public function close() {
        
    }

}
