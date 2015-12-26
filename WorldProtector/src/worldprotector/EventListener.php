<?php

/*
 * WorldProtector plugin for PocketMine-MP
 * Copyright (C) 2015 Jack Noordhuis
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

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
