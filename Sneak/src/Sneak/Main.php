<?php

/*
 * The MIT License
 *
 * Copyright 2015 Jack Noordhuis (CrazedMiner) CrazedMiner.weebly.com.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Sneak;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\entity\Entity;

class Main extends PluginBase {
    
    private $sneaking = array();

    public function onLoad() {
        $this->getLogger()->info(TextFormat::BLUE . "Loading Sneak v1.1 By CrazedMiner....");
    }

    public function onEnable() {
        $this->getLogger()->info(TextFormat::GREEN . "Sneak v1.1 By CrazedMiner Enabled!");
    }

    public function onDisable() {
        $this->getLogger()->info(TextFormat::GREEN . "Sneak v1.1 By CrazedMiner Disabled!");
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        if(strtolower($cmd->getName()) === "sneak") {
            if(isset($args[0])) {
                $name = $args[0];
                $target = $this->getServer()->getPlayer($name);
                if($sender->hasPermission("sneak.command.other")){
                    if($target instanceof Player) {
                        if($this->isSneaking($target)) {
                            $this->disableSneak($target, $sender);
                        }
                        else {
                            $this->enableSneak($target, $sender);
                        }
                    }
                    else {
                        $sender->sendMessage(TextFormat::RED . "Sorry, " . $args[0] . " is not online!");
                    }
                }
                else {
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                }
                
            }
            else {
                if($sender instanceof Player) {
                    if($sender->hasPermission("sneak.command.self")) {
                        if($this->isSneaking($sender)) {
                            $this->disableSneak($sender, NULL);
                        }
                        else {
                            $this->enableSneak($sender, NULL);
                        }
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
    
    public function isSneaking(Player $player) {
        return in_array($player->getName(), $this->sneaking);
    }
    
    public function enableSneak($player, $sender) {
        $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_SNEAKING, true);
        $this->sneaking[$player->getName()] = $player->getName();
        
        if($sender !== NULL) {
            $player->sendMessage(TextFormat::AQUA . $sender->getName() . TextFormat::RESET . TextFormat::GOLD . " has Enabled Sneaking for you!");
            $sender->sendMessage(TextFormat::GOLD . "Enabled Sneaking for " . TextFormat::AQUA . $player->getName()) . TextFormat::RESET . TextFormat::GOLD . "!";
        }
        else {
            $player->sendMessage(TextFormat::GOLD . "You have Enabled sneaking!");
        }
    }
    
    public function disableSneak($player, $sender) {
        $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_SNEAKING, false);
        unset($this->sneaking[$player->getName()]);
        
        if($sender !== NULL) {
            $player->sendMessage(TextFormat::AQUA . $sender->getName() . TextFormat::RESET . TextFormat::GOLD . " has Disabled Sneaking for you!");
            $sender->sendMessage(TextFormat::GOLD . "Disabled Sneaking for " . TextFormat::AQUA . $player->getName() . TextFormat::RESET . TextFormat::GOLD . "!");
        }
        else {
            $player->sendMessage(TextFormat::GOLD . "You have Disabled sneaking!");
        }
    }

}
