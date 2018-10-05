<?php

namespace jacknoordhuis\inventoryclear\command;

use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

use jacknoordhuis\inventoryclear\Main;

class ViewInv implements CommandExecutor {
        
        private $plugin = null;

        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
        }

        public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
                if($sender instanceof Player) {
                        if(isset($args[0])) {
                                $name = $args[0];
                                $target = $this->plugin->getServer()->getPlayer($name);
                                if($sender->hasPermission("inventoryclear.viewinv")) {
                                        if($target instanceof Player) {
                                                $this->plugin->viewInventory($sender, $target);
                                        } else {
                                                $sender->sendMessage(TF::RED . "Sorry, " . $name . " is not online!");
                                        }
                                } else {
                                        $sender->sendMessage(TF::RED . "You don't have permissions to use this command.");
                                }
                        } elseif($this->plugin->isViewing($sender->getName())) {
                                $this->plugin->stopViewing($sender->getName());
                        } else {
                                $sender->sendMessage(TF::RED . "Please specify a player!");
                        }
                } else {
                        $sender->sendMessage(TF::RED . "Please run this command in-game!");
                }
        }

}
