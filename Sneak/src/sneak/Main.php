<?php

/*
 * Sneak plugin for PocketMine-MP
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

namespace jacknoordhuis\sneak;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use pocketmine\Player;
use pocketmine\entity\Entity;

use sneak\command\SneakCommand;

class Main extends PluginBase {

        public function onEnable() {
                $this->getCommand("sneak")->setExecutor(new SneakCommand($this));
                $this->getLogger()->info(TF::AQUA . "Sneak v1.1.1" . TF::GREEN . " by " . TF::YELLOW . "Jack Noordhuis" . TF::GREEN . ", Enabled successfully!");
        }
    
        public function toggleSneak(Player $player) {
                $player->setSneaking(!$player->isSneaking());
        }

}
