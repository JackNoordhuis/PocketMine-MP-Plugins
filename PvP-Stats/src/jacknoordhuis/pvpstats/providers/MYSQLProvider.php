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

namespace Cjacknoordhuis\pvpstats\providers;

use pocketmine\utils\Config;
use pocketmine\IPlayer;

use jacknoordhuis\pvpstats\Main;

class MYSQLProvider implements ProviderInterface {
    
    protected $plugin;
    
    protected $database;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;        
        $settings = (new Config($this->plugin->getDataFolder() . "Settings.yml"))->getAll()["mysql-settings"];
        
        if(!isset($settings["host"]) or !isset($settings["user"]) or !isset($settings["password"]) or !isset($settings["database"]) or !isset($settings["port"])) {
            $this->plugin->getLogger()->critical("Invalid MySQL Settings!");
            return;
        }
        
        $this->database = new \mysqli($settings["host"], $settings["user"], $settings["password"], $settings["database"], $settings["port"]);
        if($this->database->connect_error) {
            $this->plugin->getLogger()->critical("Couldn't connect to MySQL:" . $this->database->connect_error);
            return;
        }
        
        $resource = $this->plugin->getResource("mysql.sql");
        $this->database->query(stream_get_contents($resource));
        fclose($resource);
        $this->plugin->getLogger()->info("Data Provider set to MySQL!");
    }
    
    public function getPlayer(IPlayer $player) {
        $name = strtolower($player->getName());
        $result = $this->database->query("SELECT * FROM pvp_stats WHERE name = '" . $this->database->escape_string($name). "'");
        if($result instanceof \mysqli_result) {
            $data = $result->fetch_assoc();
            $result->free();
            if(isset($data["name"]) and strtolower($data["name"] === $name)) {
                unset($data["name"]);
                return $data;
            }
        }
        return null;
    }
    
    public function getData($player) {
        $name = strtolower($player);
        $result = $this->database->query("SELECT * FROM pvp_stats WHERE name = '" . $this->database->escape_string($name). "'");
        if($result instanceof \mysqli_result) {
            $data = $result->fetch_assoc();
            $result->free();
            if(isset($data["name"]) and strtolower($data["name"] === $name)) {
                unset($data["name"]);
                return $data;
            }
        }
        return null;
    }
    
    public function playerExists(IPlayer $player) {
        if($this->getPlayer($player) !== null) {
            return true;
        }
        return null;
    }
    
    public function addPlayer(IPlayer $player) {
        $name = strtolower($player->getName());
        $this->database->query("INSERT INTO pvp_stats
            (name, kills, deaths)
            VALUES
            ('" . $this->database->escape_string($name) . "', '0', '0')
            ");
    }
    
    public function removePlayer(IPlayer $player) {
        $name = strtolower($player->getName());
        if($this->playerExists($player)) {
            $this->database->query("DELETE FROM pvp_stats WHERE name = '" . $name . "'");
        }else {
            return null;
        }
    }
    
    public function updatePlayer(IPlayer $player, $type) {
        $name = strtolower($player->getName());
        $this->database->query("UPDATE pvp_stats SET " . $type . " = " . $type . " + 1 WHERE name = '" . $name . "'");
    }
    
    public function close() {
        
    }

}
