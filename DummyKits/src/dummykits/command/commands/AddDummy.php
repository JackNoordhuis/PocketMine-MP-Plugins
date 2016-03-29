<?php

namespace dummykits\command\commands;

use pocketmine\command\CommandSender;
use pocketmine\Player;

use dummykits\Main;
use dummykits\command\DummyCommand;

abstract class AddDummy extends DummyCommand {
        
        public function __construct(Main $plugin) {
                parent::__construct($plugin, "adddummy", "Add's a DummyKit!", "/AddDummy <name>");
                $this->setPermission("dummykits.command.add");
        }
        
        public function onExecute(CommandSender $sender, array $args) {
                if(isset($args[0])) { // dummy with custom name
                        if($sender instanceof Player) {
                                $this->plugin->dummyManager->spawn(Main::translateColors($args[0]), "", $sender->getLevel(), $sender, $sender->getYaw(), $sender->getPitch(), $sender->getInventory()->getItemInHand(), $sender->getInventory()->getArmorContents());
                        } else {
                                $sender->sendMessage("error");
                        }
                } elseif(isset($args[1])) { // dummy with name and description
                        $this->plugin->dummyManager->spawn(Main::translateColors($args[0]), Main::translateColors($args[1]), $sender->getLevel(), $sender, $sender->getYaw(), $sender->getPitch(), $sender->getInventory()->getItemInHand(), $sender->getInventory()->getArmorContents());
                } else { // complete clone
                        if($sender instanceof Player) {
                                $this->plugin->dummyManager->spawn($sender->getName(), "", $sender->getLevel(), $sender, $sender->getYaw(), $sender->getPitch(), $sender->getInventory()->getItemInHand(), $sender->getInventory()->getArmorContents());
                        } else {
                                $sender->sendMessage("error");
                        }
                }
        }
}
