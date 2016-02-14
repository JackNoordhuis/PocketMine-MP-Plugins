<?php

namespace inventoryclear\command;

use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use inventoryclear\Main;

class ClearInv implements CommandExecutor {

        private $plugin = null;

        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
        }

        public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
                if(isset($args[0])) {
                        $name = $args[0];
                        $target = $this->plugin->getServer()->getPlayer($name);
                        if($sender->hasPermission("inventoryclear.clearinv.other")) {
                                if($target instanceof Player) {
                                        $this->plugin->clearInventory($target, $sender);
                                } else {
                                        $sender->sendMessage(TextFormat::RED . "Sorry, " . $name . " is not online!");
                                }
                        } else {
                                $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                        }
                } else {
                        if($sender instanceof Player) {
                                if($sender->hasPermission("inventoryclear.clerinv.self")) {
                                        $this->plugin->clearInventory($sender);
                                } else {
                                        $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                                }
                        } else {
                                $sender->sendMessage(TextFormat::RED . "Please run this command in-game!");
                        }
                }
        }

}
