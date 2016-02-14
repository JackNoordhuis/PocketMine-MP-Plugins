<?php

namespace inventoryclear;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;

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
    
    public function onInventoryClose(InventoryCloseEvent $event) {
            $player = $event->getPlayer();
            if(isset($this->plugin->viewing[$player->getName()])) {
                    $old = $this->plugin->viewing[$player->getName()];
                    $player->getInventory()->setArmorContents($old["armor"]);
                    $player->getInventory()->sendArmorContents($player);
                    $player->getInventory()->setContents($old["contents"]);
                    $player->getInventory()->sendContents($player);
                    unset($this->plugin->viewing[$player->getName()]);
            }
            return;
    }
    
    public function onDeath(PlayerDeathEvent $event) {
        if($this->plugin->settings["events"]["death"]) {
            $event->setDrops(array(Item::get(0, 0, 0)));
        }
    }
    
    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        if($this->plugin->settings["events"]["leave"]) {
            $this->plugin->clearInventory($player);
        }
    }
}

