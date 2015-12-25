<?php

namespace worldprotector;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class EventListener implements Listener {
    
        private $plugin;
    
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
        }
        
        public function onBreak(BlockBreakEvent $event) {
                if($event->getPlayer()->hasPermission("worldprotector.block.break")) {
                        $event->setCancelled(false);
                } else {
                    $event->setCancelled(true);
                }
        }
        
        public function onPlace(BlockPlaceEvent $event) {
                if($event->getPlayer()->hasPermission("worldprotector.block.place")) {
                        $event->setCancelled(false);
                } else {
                    $event->setCancelled(true);
                }
        }
        
        public function onInteract(PlayerInteractEvent $event) {
                if($event->getPlayer()->hasPermission("worldprotector.block.interact")) {
                        $event->setCancelled(false);
                } else {
                    $event->setCancelled(true);
                }
        }
        
        public function onDamage(EntityDamageEvent $event) {
                $victim = $event->getEntity();
                if($victim instanceof Player) {
                        if($victim->hasPermission("worldprotector.player.damage")) {
                                $event->setCancelled(false);
                        } elseif($victim->getLastDamageCause() instanceof EntityDamageByEntityEvent) {
                                $attacker = $victim->getLastDamageCause()->getDamager();
                                if($attacker instanceof Player) {
                                        if($attacker->hasPermission("worldprotector.player.attack")) {
                                                $event->setCancelled(false);
                                        } else {
                                                $event->setCancelled(true);
                                        }
                                }
                        } else {
                                $event->setCancelled(true);
                        }
                }
        }
    
}
