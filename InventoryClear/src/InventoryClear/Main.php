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

namespace InventoryClear;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;


class Main extends PluginBase implements Listener {

    public function onLoad() {
        $this->getLogger()->info(TextFormat::BLUE . "Loading InventoryClear v1.0 By CrazedMiner....");
    }

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if(!file_exists($this->getDataFolder() . "config.yml")) {
            @mkdir($this->getDataFolder());
             file_put_contents($this->getDataFolder() . "config.yml",$this->getResource("config.yml"));
        }
        $this->getLogger()->info(TextFormat::GREEN . "InventoryClear v1.0 By CrazedMiner Enabled!");
    }

    public function onDisable() {
        $this->getLogger()->info(TextFormat::DARK_GREEN . "InventoryClear v1.0 By CrazedMiner Disabled!");
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        if(strtolower($cmd->getName()) === "clearinv") {
            if($sender->hasPermission("inventoryclear.command.clearinv")) {
                if(count($args) === 1) {
                    $name = $args[0];
                    $target = $this->getServer()->getPlayer($name);
                    if($target instanceof Player) {
                    $target->getInventory()->clearAll();
                    $target->sendMessage("Your inventory has been cleared by " . $sender->getName());
                    $sender->sendMessage("Cleared " . $target->getName() . "'s inventory.");
                    }
                    else {
                        $sender->sendMessage("Sorry, " . $args[0] . " is not online!");
                    }
                
                }
                else {
                    $sender->sendMessage("Usage: /clearinv <playername>");
                }
            }
            else {
                $sender->sendMessage("You don't have permissions to use this command.");
            }
        }
    }
    
    public function onJoin(PlayerJoinEvent $event) {
        $this->clearjoin = $this->getConfig()->get("Clear on Join");
        if($this->clearjoin == true) {
            $event->getPlayer()->getInventory()->clearAll();
        }
    }
    
    public function onDeath(PlayerDeathEvent $event) {
        $this->clearjoin = $this->getConfig()->get("Clear on Death");
        if($this->clearjoin == true) {
            $event->setDrops(array());
        }
    }
    
    public function onQuit(PlayerQuitEvent $event) {
        $this->clearjoin = $this->getConfig()->get("Clear on Quit");
        if($this->clearjoin == true) {
            $event->getPlayer()->getInventory()->clearAll();
        }
    }
    
}
