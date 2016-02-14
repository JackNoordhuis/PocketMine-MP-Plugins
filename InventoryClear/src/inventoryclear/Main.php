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

namespace inventoryclear;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\inventory\PlayerInventory;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;

use inventoryclear\command\ClearInv;
use inventoryclear\command\ViewInv;

class Main extends PluginBase {

        public $settings = [];
        
        public $viewing = [];

        public function onEnable() {
                $this->loadConfigs();
                $this->registerCommands();
                new EventListener($this);
                $this->getLogger()->info(TextFormat::GREEN . "InventoryClear v1.3 by JackNoordhuis has been enabled!");
        }

        public function loadConfigs() {
                $this->saveResource("Settings.yml");
                $this->settings = (new Config($this->getDataFolder() . "Settings.yml", Config::YAML))->getAll();
        }

        public function registerCommands() {
                if((bool) $this->settings["commands"]["clearinv"]["self"] or (bool) $this->settings["commands"]["clearinv"]["other"]) {
                        $cmd = (new PluginCommand("clearinv", $this));
                        $cmd->setDescription("Clear your own or another players inventory");
                        $cmd->setExecutor(new ClearInv($this));
                        $this->getServer()->getCommandMap()->register("ic", $cmd);
                }
                if($this->settings["commands"]["viewinv"]) {
                        $cmd = new PluginCommand("viewinv", $this);
                        $cmd->setDescription("View a players inventory");
                        $cmd->setExecutor(new ViewInv($this));
                        $this->getServer()->getCommandMap()->register("ic", $cmd);
                }
        }

        public function onDisable() {
                $this->getLogger()->info(TextFormat::DARK_GREEN . "InventoryClear v1.3 by JackNoordhuis has been disabled!");
        }

        public function clearInventory(Player $player, CommandSender $sender = null) {
                if($player instanceof Player and $player->getInventory()instanceof PlayerInventory) {
                        $player->getInventory()->clearAll();
                }
                if($sender instanceof CommandSender) {
                        $player->sendMessage(TextFormat::GOLD . "Your inventory has been cleared by " . TextFormat::RESET . TextFormat::BOLD . TextFormat::AQUA . $sender->getName() . TextFormat::RESET . TextFormat::GOLD . "!");
                        $sender->sendMessage(TextFormat::GOLD . "Successfully cleared " . TextFormat::RESET . TextFormat::BOLD . TextFormat::AQUA . $player->getName() . TextFormat::RESET . TextFormat::GOLD . "'s inventory!");
                } else {
                        $player->sendMessage(TextFormat::GOLD . "Your inventory was cleared successfully!");
                }
        }
        
        public function viewInventory(Player $player, Player $target) {
                $this->viewing[$player->getName()] = [
                    "armor" => $player->getInventory()->getArmorContents(),
                    "contents" => $player->getInventory()->getContents()
                ];
                $player->getInventory()->setArmorContents($target->getInventory()->getArmorContents());
                $player->getInventory()->sendArmorContents($player);
                $player->getInventory()->setContents($target->getInventory()->getContents());
                $player->getInventory()->sendContents($player);
                $player->addWindow($player->getInventory());
        }

}
