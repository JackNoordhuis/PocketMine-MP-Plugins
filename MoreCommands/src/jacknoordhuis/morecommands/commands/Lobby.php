<?php

namespace jacknoordhuis\morecommands\commands;

use jacknoordhuis\morecommands\Main;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Lobby extends PluginBase implements CommandExecutor{
    
    public function __construct(Main $plugin){
        $this->main = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label,array $args){
        if(strtolower($cmd->getName()) === "lobby" && $this->main->getConfig()->get("Lobby-Command-Enabled") === true) {
            if($sender instanceof Player) {
                if($sender->hasPermission("morecommands.command.lobby")) {
                    $sender->sendMessage($this->main->getConfig()->get("Teleport-to-Lobby-Message"));
                    $sender->teleport($this->main->getServer()->getDefaultLevel()->getSpawnLocation());
                    return true;
                }
                else {
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                }
            }
            else {
                $sender->sendMessage(TextFormat::RED . "Please use this command in-game!");
                return true;
            }
        }
    }
}
