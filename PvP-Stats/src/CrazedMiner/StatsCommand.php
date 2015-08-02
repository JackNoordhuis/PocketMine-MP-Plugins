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
use pocketmine\Player;
use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use CrazedMiner\Main;

class StatsCommand extends PluginBase implements CommandExecutor{
    
    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){        
        if(strtolower($cmd->getName()) === "stats") {
            if(isset($args[0])) {
                $name = $args[0];
                if($sender->hasPermission("pvp-stats.command.other")) {
                    if($this->plugin->getData($name) !== null) {
                        $sender->sendMessage($name . "('s) Stats:\n- Kills: " . $this->plugin->getData($name)["kills"] . "\n- Deaths: " . $this->plugin->getData($name)["deaths"] . "\n- K/D Ratio: " . round(($this->plugin->getData($name)["kills"] / $this->plugin->getData($name)["deaths"]), 3));
                    }
                    else {
                    $sender->sendMessage(TextFormat::RED . "Sorry, stats for " . $name . " don't exist.");
                    }
                }
                else {
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                }
            }
            else {
                if($sender instanceof Player) {
                    if($sender->hasPermission("pvp-stats.command.self")) {
                        $sender->sendMessage("Your Stats:\n- Kills: " . $this->plugin->getPlayer($sender)["kills"] . "\n- Deaths: " . $this->plugin->getPlayer($sender)["deaths"]  . "\n- K/D Ratio: " . round(($this->plugin->getPlayer($sender)["kills"] / $this->plugin->getPlayer($sender)["deaths"]), 3));
                    }
                    else {
                        $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                    }
                }
                else {
                    $sender->sendMessage(TextFormat::RED . "Please run this command in-game!");
                }
            }
        }
    }
}
