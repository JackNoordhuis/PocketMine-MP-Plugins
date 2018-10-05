<?php

namespace jacknoordhuis\fly;

use pocketmine\Player;
use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class FlyCommand implements CommandExecutor {

        private $plugin;

        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
        }

        public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
                if(strtolower($cmd->getName()) === "fly") {
                        if($sender instanceof Player) {
                                if($sender->hasPermission("fly.command.fly")) {
                                        if($this->plugin->hasFlyingSession($sender)) {
                                                $session = $this->plugin->getFlyingSession($sender);
                                                $this->plugin->getFlyingSession($sender)->setFlying(!$session->getFlying());
                                                $sender->sendMessage($session->getFlying() ? TF::GREEN . "You have enabled flying!" : TF::GOLD . "You have disabled flying!");
                                        }
                                } else {
                                        $sender->sendMessage(TF::RED . "You don't have permissions to use the 'fly' command!");
                                }
                        } else {
                                $sender->sendMessage(TF::RED . "You must use the 'fly' command in-game!");
                        }
                }
        }

}
