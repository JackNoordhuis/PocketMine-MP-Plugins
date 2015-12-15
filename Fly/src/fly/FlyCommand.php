<?php

namespace fly;

use pocketmine\Player;
use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

use fly\Main;

class FlyCommand implements CommandExecutor {
    
    private $plugin;
    
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        if(strtolower($cmd->getName()) === "fly") {
            if($sender instanceof Player) {
                if($this->plugin->hasFlyingSession($sender)) {
                    $session = $this->plugin->getFlyingSession($sender);
                    if($session->getFlying()) {
                        $this->plugin->getFlyingSession($sender)->setFlying(false);
                        $sender->sendMessage(TF::GOLD . "You have disabled flying!");
                    } else {
                        $this->plugin->getFlyingSession($sender)->setFlying(true);
						$sender->sendMessage(TF::GREEN . "You have enabled flying!");
					}
                }
            } else {
                $sender->sendMessage(TF::RED . "You must use the 'fly' command in-game!");
            }
        }
    }
    
}