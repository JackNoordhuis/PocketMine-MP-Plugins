<?php

namespace jacknoordhuis\pvpstats;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use CrazedMiner\Main;

class StatsCommand extends PluginBase implements CommandExecutor{
    
    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){        
        if(strtolower($cmd->getName()) === "stats") {
            if(isset($args[0])) {
                $name = $args[0];
                if($sender->hasPermission("pvp-stats.command.other")) {
                    if($this->plugin->getData($name) !== null) {
                        if($this->plugin->getData($name)["kills"] >= 1 and $this->plugin->getData($name)["deaths"] >= 1) {
                            $sender->sendMessage($this->plugin->translateColors(str_replace(array("@player", "@kills", "@deaths", "@kdratio"), array($name, $this->plugin->getData($name)["kills"], $this->plugin->getData($name)["deaths"], (round((($this->plugin->getData($name)["kills"]) / ($this->plugin->getData($name)["deaths"])), 3))), (new Config($this->plugin->getDataFolder() . "Settings.yml"))->getAll()["other-command-format"])));
                        }else {
                            $sender->sendMessage($this->plugin->translateColors(str_replace(array("@player", "@kills", "@deaths", "@kdratio"), array($name, $this->plugin->getData($name)["kills"], $this->plugin->getData($name)["deaths"], ("&r&cN&r&7/&r&cA&r")), (new Config($this->plugin->getDataFolder() . "Settings.yml"))->getAll()["other-command-format"])));
                        }
                    }
                    else {
                    $sender->sendMessage(TextFormat::RED . "Sorry, stats for " . $name . " don't exist.");
                    }
                }
                else {
                    $sender->sendMessage(TextFormat::RED . "You don't have permissions to use this command.");
                }
            }
            else {
                if($sender instanceof Player) {
                    if($sender->hasPermission("pvp-stats.command.self")) {
                        if($this->plugin->getPlayer($sender)["kills"] >= 1 and $this->plugin->getPlayer($sender)["deaths"] >= 1) {
                            $sender->sendMessage($this->plugin->translateColors(str_replace(array("@kills", "@deaths", "@kdratio"), array($this->plugin->getPlayer($sender)["kills"], $this->plugin->getPlayer($sender)["deaths"], (round(($this->plugin->getPlayer($sender)["kills"] / $this->plugin->getPlayer($sender)["deaths"]), 3))), (new Config($this->plugin->getDataFolder() . "Settings.yml"))->getAll()["self-command-format"])));
                        }else {
                            $sender->sendMessage($this->plugin->translateColors(str_replace(array("@kills", "@deaths", "@kdratio"), array($this->plugin->getPlayer($sender)["kills"], $this->plugin->getPlayer($sender)["deaths"], ("&r&cN&r&7/&r&cA&r")), (new Config($this->plugin->getDataFolder() . "Settings.yml"))->getAll()["self-command-format"])));
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
}
