<?php

namespace dummykits\command\commands;

use pocketmine\command\CommandSender;

use pocketmine\Player;

use dummykits\Main;
use dummykits\command\DummyCommand;

abstract class EditDummy extends DummyCommand {
        
        public function __construct(Main $plugin) {
                parent::__construct($plugin, "editdummy", "Allow's editing of an existing Dummy!", "/EditDummy", "changedummy");
                $this->setPermission("dummykits.command.edit");
        }
        
        public function onCommand(CommandSender $sender, $label, array $args) {
                if(!$this->testPermission($sender)) {
                        return true;
                }
                
                switch(count($args) - 1) {
                        case 0:
                                if($sender instanceof Player) {
                                        $this->plugin->dummyManager->spawn(Main::translateColors($args[0]), "", $sender->getLevel(), $sender, $sender->getYaw(), $sender->getPitch(), $sender->getInventory()->getItemInHand(), $sender->getInventory()->getArmorContents());
                                } else {
                                        $sender->sendMessage("error");
                                }
                                break;
                        case 1:
                                if($sender instanceof Player) {
                                        $this->plugin->dummyManager->spawn(Main::translateColors($args[0]), Main::translateColors($args[1]), $sender->getLevel(), $sender, $sender->getYaw(), $sender->getPitch(), $sender->getInventory()->getItemInHand(), $sender->getInventory()->getArmorContents());
                                } else {
                                        $sender->sendMessage("error");
                                }
                                break;
                        default:
                                if($sender instanceof Player) {
                                        $this->plugin->dummyManager->spawn($sender->getName(), "", $sender->getLevel(), $sender, $sender->getYaw(), $sender->getPitch(), $sender->getInventory()->getItemInHand(), $sender->getInventory()->getArmorContents());
                                } else {
                                        $sender->sendMessage("error");
                                }
                                break;
                }
        }
}
