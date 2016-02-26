<?php

namespace dummykits\command\commands;

use pocketmine\command\CommandSender;

use pocketmine\Player;

use dummykits\Main;

class EditDummy extends DummyCommand {
        
        /** @var Main */
        private $plugin = null;
        
        public function __construct($name, $description = "") {
                parent::__construct($name, $description);
                $this->plugin = Main::getInstance();
                $this->setPermission("dummykits.command.add");
        }
        
        public function execute(CommandSender $sender, $label, array $args) {
                if(!$this->testPermission($sender)) {
                        return true;
                }
                
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
