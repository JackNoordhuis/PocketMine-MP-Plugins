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

use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

use PositionTeller\Main;

class TogglePosCommand implements CommandExecutor {
    
    private $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }
    
    public function getPlugin() {
        return $this->plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        if(strtolower($cmd) === "togglepos") {
            if($sender instanceof Player) {
                if($sender->hasPermission("positionteller.command")) {
                    if($this->getPlugin()->isActive($sender)) {
                        $this->getPlugin()->removeActive($sender);
                        $sender->sendMessage(TF::GOLD . "You have turned off PositionTeller!");
                    } else {
                        $this->getPlugin()->addActive($sender);
                        $sender->sendMessage(TF::GREEN . "You have activated PositionTeller!");
                    }
                } else {
                    $sender->sendMessage(TF::RED . "You do not have permission to use this command!");
                }
            } else {
                    $sender->sendMessage(TF::RED . "Please use this command in-game!");
                }
        }
    }

}
