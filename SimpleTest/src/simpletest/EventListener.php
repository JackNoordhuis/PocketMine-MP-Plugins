<?php

namespace simpletest;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerInteractEvent;

class EventListener implements Listener {
        
        private $plugin = null;
        
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
        }
        
        public function onMove(PlayerMoveEvent $event) {
                $player = $event->getPlayer();
                $player->sendMessage("You're traveling at " . round($event->getFrom()->distance($event->getTo()), 5) . " blocks a movement.");
        }
        
        public function onInteract(PlayerInteractEvent $event) {
                $player = $event->getPlayer();
                $player->sendMessage("You reached " . round($player->distance($event->getBlock()), 5) . " blocks.");
        }
        
}
