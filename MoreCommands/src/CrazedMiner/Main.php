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

namespace MoreCommands;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Main extends PluginBase{
    
  public function onLoad(){
        $this->getLogger()->info(TextFormat::BLUE . "Loading MoreCommands v1.0 By CrazedMiner....");
    }
    
    public function onEnable(){
        $this->saveDefaultConfig();
        $config = $this->getConfig();
        $this->spawnm = $config->get("Teleport to Spawn Message");
        $this->hubm = $config->get("Teleport to Hub Message");
        $this->lobbym = $config->get("Teleport to Lobby Message");
        $this->getLogger()->info(TextFormat::GREEN . "MoreCommands v1.0 By CrazedMiner Enabled!");
    }
    
    public function onDisable(){
        $this->getLogger()->info(TextFormat::GREEN . "MoreCommands v1.0 By CrazedMiner Disabled!");
    }
    
    public function onCommand(CommandSender $sender, Command $command,$label,array $args){
    $defaultspawn = $this->getServer()->getDefaultLevel()->getSpawnLocation();
        switch($command->getName()){
            case "spawn":{
                if($sender instanceof Player){
                    $sender->sendMessage($this->spawnm);
                    $sender->getPlayer()->teleport($defaultspawn);
                }else{
                    $sender->sendMessage(TextFormat::RED . "Command must be used in-game!");
                }
                break;
            }
            case "hub":{
                if($sender instanceof Player){
                    $sender->sendMessage($this->hubm);
                    $sender->getPlayer()->teleport($defaultspawn);
                }else{
                    $sender->sendMessage(TextFormat::RED . "Command must be used in-game!");
                }
                break;
            }
            case "lobby":{
                if($sender instanceof Player){
                    $sender->sendMessage($this->lobbym);
                    $sender->getPlayer()->teleport($defaultspawn);
                }else{
                    $sender->sendMessage(TextFormat::RED . "Command must be used in-game!");
                }
                break;
            }
        }
    }
}
