<?php

namespace dummykits\command\commands;

use pocketmine\command\CommandSender;
use pocketmine\Player;

use dummykits\Main;
use dummykits\command\DummyCommand;

class AddDummy extends DummyCommand {
        
        public function __construct(Main $plugin) {
                parent::__construct($plugin, "adddummy", "Add's a DummyKit", "/AddDummy <name>");
                $this->setPermission("dummykits.command.add");
        }
        
        public function onExecute(CommandSender $sender, array $args) {
                switch(count($args) - 1) {
                        case 0:
                                if($sender instanceof Player) {
                                        $this->plugin->dummyManager->spawn(Main::translateColors($args[0]), "", $sender->getLevel(), $sender, $sender->getYaw(), $sender->getPitch(), $sender->getInventory()->getItemInHand(), $sender->getInventory()->getArmorContents());
                                } else {
                                        return "You must be a player to execute this command!";
                                }
                                break;
                        case 1:
                                if($sender instanceof Player) {
                                        $this->plugin->dummyManager->spawn(Main::translateColors($args[0]), Main::translateColors($args[1]), $sender->getLevel(), $sender, $sender->getYaw(), $sender->getPitch(), $sender->getInventory()->getItemInHand(), $sender->getInventory()->getArmorContents());
                                } else {
                                        return "You must be a player to execute this command!";
                                }
                                break;
                        default:
                                if($sender instanceof Player) {
                                        $this->plugin->dummyManager->spawn($sender->getName(), "", $sender->getLevel(), $sender, $sender->getYaw(), $sender->getPitch(), $sender->getInventory()->getItemInHand(), $sender->getInventory()->getArmorContents());
                                } else {
                                        return "You must be a player to execute this command!";
                                }
                                break;
                }
        }
}
