<?php

/**
 * AutoInv EventListener class
 * 
 * Created on Mar 24, 2016 at 10:12:22 PM
 *
 * @author Jack
 */

namespace autoinv;

use pocketmine\event\Listener;
use pocketmine\inventory\InventoryHolder;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityExplodeEvent;

use autoinv\Main;

class EventListener implements Listener {
        
        /** @var $plugin Main */
        private $plugin;
        
        /**
         * Construct a new event listener class
         * 
         * @param Main $plugin
         */
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
                $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
        }
        
        /**
         * Get the owning plugin
         * 
         * @return Main
         */
        public function getPlugin() {
                return $this->plugin;
        }
        
        /**
         * Handles autoinv block breaking
         * 
         * @param BlockBreakEvent $event
         * 
         * @return null
         * 
         * @priority HIGHEST
         */
        public function onBreak(BlockBreakEvent $event) {
                if($event->isCancelled()) {
                        return;
                } else {
                        foreach($event->getDrops() as $drop) {
                                $event->getPlayer()->getInventory()->addItem($drop);
                        }
                }
        }
        
        /**
         * Handles autoinv entity death
         * 
         * @param EntityDeathEvent $event
         * 
         * @return null
         * 
         * @priority HIGHEST
         */
        public function onDeath(EntityDeathEvent $event) {
                $victim = $event->getEntity();
                $killer = $victim->getLastDamageCause()->getDamager();
                if($killer instanceof InventoryHolder) {
                        foreach($event->getDrops() as $drop) {
                                $killer->getInventory()->addItem($drop);
                        }
                } else {
                        $event->setDrops([]);
                }
                return;
        }
        
        /**
         * Handles autoinv entity exploding
         * 
         * @param EntityExplodeEvent $event
         * 
         * @return null
         * 
         * @priority HIGHEST
         */
        public function onExplode(EntityExplodeEvent $event) {
                if($event->isCancelled()) {
                        return;
                } else {
                        $explosive = $event->getEntity();
                        $closest = PHP_INT_MAX;
                        $entity = null;
                        foreach($explosive->getLevel()->getNearbyEntities($explosive->getBoundingBox()->grow(6, 6, 6)) as $nearby) {
                                if($explosive->distance($nearby) <= $closest) {
                                        $entity = $nearby;
                                }
                        }
                        if($nearby instanceof InventoryHolder) {
                                foreach($event->getYield() as $yield) {
                                        $nearby->getInventory()->addItem($yield);
                                }
                        } else {
                                $event->setYield([]);
                        }
                }
                return;
        }
}
