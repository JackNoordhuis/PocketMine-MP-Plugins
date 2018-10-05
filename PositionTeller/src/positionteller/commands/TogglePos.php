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

namespace jacknoordhuis\positionteller\commands;

use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

use jacknoordhuis\positionteller\Main;

class TogglePos implements CommandExecutor {
    
        
    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function getPlugin() {
        return $this->plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        if(strtolower($cmd->getName()) === "togglepos") {
            if(isset($args[0])) {
                if($sender->hasPermission("positionteller.command.togglepos.other")) {
                    $name = $args[0];
                    $target = $this->getPlugin()->getServer()->getPlayer($name);
                    if($target instanceof Player) {
                        if($this->getPlugin()->isActive($target)) {
                            $this->getPlugin()->removeActive($target);
                            $sender->sendMessage(str_replace("@reciver", $target->getName(), Main::translateColors($this->getPlugin()->getConfigValue("messages.togglepos.other.succeed.de-activate.sender"))));
                            $target->sendMessage(str_replace("@sender", $sender->getName(), Main::translateColors($this->getPlugin()->getConfigValue("messages.togglepos.other.succeed.de-activate.reciver"))));
                            return true;
                        } else {
                            $this->getPlugin()->addActive($target);
                            $sender->sendMessage(str_replace("@reciver", $target->getName(), Main::translateColors($this->getPlugin()->getConfigValue("messages.togglepos.other.succeed.activate.sender"))));
                            $target->sendMessage(str_replace("@sender", $sender->getName(), Main::translateColors($this->getPlugin()->getConfigValue("messages.togglepos.other.succeed.activate.reciver"))));
                            return true;
                        }
                    } else {
                        $sender->sendMessage(str_replace("@reciver", $name, Main::translateColors($this->getPlugin()->getConfigValue("messages.togglepos.other.fail.default"))));
                        return true;
                    }
                } else {
                    $sender->sendMessage(Main::translateColors($this->getPlugin()->getConfigValue("messages.togglepos.other.fail.permisson")));
                    return true;
                }
            } else {
                if($sender instanceof Player) {
                    if($sender->hasPermission("positionteller.command.togglepos.self")) {
                        if($this->getPlugin()->isActive($sender)) {
                            $this->getPlugin()->removeActive($sender);
                            $sender->sendMessage(Main::translateColors($this->getPlugin()->getConfigValue("messages.togglepos.self.succeed.de-activate")));
                            return true;
                        } else {
                            $this->getPlugin()->addActive($sender);
                            $sender->sendMessage(Main::translateColors($this->getPlugin()->getConfigValue("messages.togglepos.self.succeed.activate")));
                            return true;
                        }
                    } else {
                        $sender->sendMessage(Main::translateColors($this->getPlugin()->getConfigValue("messages.togglepos.self.fail.permission")));
                        return true;
                    }
                } else {
                    $sender->sendMessage(Main::translateColors($this->getPlugin()->getConfigValue("messages.togglepos.self.fail.game")));
                    return true;
                }
            }
        }
    }

}
