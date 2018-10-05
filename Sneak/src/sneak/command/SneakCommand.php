<?php

/*
 * WorldProtector plugin for PocketMine-MP
 * Copyright (C) 2015 Jack Noordhuis
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

namespace jacknoordhuis\sneak\command;

use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use sneak\Main;

class SneakCommand implements CommandExecutor {
        
        private $plugin;
        
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
        }
        
        public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
                if(strtolower($cmd->getName()) === "sneak") {
                        if(isset($args[0])) {
                                $target = $this->plugin->getServer()->getPlayer($args[0]);
                                if($sender->hasPermission("sneak.command.other")) {
                                        if($target instanceof Player) {
                                                $this->plugin->toggleSneak($target);
                                                $target->sendMessage(TF::BOLD . TF::AQUA . $sender->getName() . TF::RESET . TF::GOLD . " has toggled sneaking for you!");
                                                $sender->sendMessage(TF::GOLD . "Toggled sneaking for " . TF::BOLD . TF::AQUA . $target->getName()) . TF::RESET . TF::GOLD . "!";
                                                return true;
                                        } else {
                                                $sender->sendMessage(TF::RED . $args[0] . "is not online!");
                                                return true;
                                        }
                                } else {
                                        $sender->sendMessage(TF::RED . "You do not have permissions to use the 'sneak' command!");
                                        return true;
                                }
                        } elseif($sender instanceof Player) {
                                if($sender->hasPermission("sneak.command.self")) {
                                        $this->plugin->toggleSneak($sender);
                                        $sender->sendMessage(TF::GOLD . "You have toggled sneaking!");
                                } else {
                                        $sender->sendMessage(TF::RED . "You do not have permissions to use the 'sneak' command!");
                                }
                        } else {
                                $sender->sendMessage(TF::RED . "You must run the 'sneak' command in-game!");
                        }
                        return false;
                }
        }
        
}
