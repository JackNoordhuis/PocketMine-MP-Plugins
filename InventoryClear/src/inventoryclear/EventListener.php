<?php

namespace inventoryclear;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
//use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\inventory\InventoryPickupArrowEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\utils\TextFormat as TF;

use inventoryclear\Main;

class EventListener implements Listener {

        private $plugin = null;

        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
                $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
        }

        public function onJoin(PlayerJoinEvent $event) {
                $player = $event->getPlayer();
                if($this->plugin->settings["events"]["join"]) {
                        $this->plugin->clearInventory($player);
                }
        }
        
        public function onArmorChange(EntityArmorChangeEvent $event) {
                if(($player = $event->getEntity()) instanceof Player) {
                        if($this->plugin->isViewing($player->getName())) {
                                $event->setCancelled(true);
                                $player->sendMessage(TF::RED . "You can't change you armor while viewing a players inventory!");
                        }
                }
        }

        public function onDrop(PlayerDropItemEvent $event) {
                $player = $event->getPlayer();
                if($this->plugin->isViewing($player->getName())) {
                        $event->setCancelled(true);
                        $player->sendMessage(TF::RED . "You can't drop items while viewing a players inventory!");
                }
        }
        
        public function onItemPickup(InventoryPickupItemEvent $event) {
                if(($player = $event->getInventory()->getHolder()) instanceof Player) {
                        if($this->plugin->isViewing($player->getName())) {
                                $event->setCancelled(true);
                                $player->sendMessage(TF::RED . "You can't pick up items while viewing a players inventory!");
                        }
                }
        }
        
        public function onArrowPickup(InventoryPickupArrowEvent $event) {
                if(($player = $event->getInventory()->getHolder()) instanceof Player) {
                        if($this->plugin->isViewing($player->getName())) {
                                $event->setCancelled(true);
                                $player->sendMessage(TF::RED . "You can't pick up arrows while viewing a players inventory!");
                        }
                }
        }
        
        public function onBlockPlace(BlockPlaceEvent $event) {
                $player = $event->getPlayer();
                if($this->plugin->isViewing($player->getName())) {
                        $event->setCancelled(true);
                        $player->sendMessage(TF::RED . "You can't place blocks while viewing a players inventory!");
                }
        }
        
        public function onBreak(BlockBreakEvent $event) {
                $player = $event->getPlayer();
                if($this->plugin->isViewing($player->getName())) {
                        $event->setCancelled(true);
                        $player->sendMessage(TF::RED . "You can't break blocks while viewing a players inventory!");
                }
        }
        
        public function onInteract(PlayerInteractEvent $event) {
                $player = $event->getPlayer();
                if($this->plugin->isViewing($player->getName())) {
                        if($event->getBlock()->getId() === Block::CHEST or $event->getBlock()->getId() === Block::TRAPPED_CHEST) {
                                $event->setCancelled(true);
                                $player->sendMessage(TF::RED . "You can't use chest's while viewing a players inventory!");
                        }
                }
        }

//    public function onInventoryClose(InventoryCloseEvent $event) {
//            $player = $event->getPlayer();
//            if(isset($this->plugin->viewing[$player->getName()])) {
//                    $this->plugin->viewing[$player->getName()]->end();
//                    unset($this->plugin->viewing[$player->getName()]);
//            }
//            return;
//    }

        public function onDeath(PlayerDeathEvent $event) {
                if($this->plugin->settings["events"]["death"]) {
                        $event->setDrops([]);
                }
        }

        public function onQuit(PlayerQuitEvent $event) {
                $player = $event->getPlayer();
                if($this->plugin->settings["events"]["leave"]) {
                        $this->plugin->clearInventory($player);
                }
        }

}
