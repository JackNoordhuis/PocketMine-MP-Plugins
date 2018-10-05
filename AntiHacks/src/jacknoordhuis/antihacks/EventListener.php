<?php

/**
 * EventListener class
 * 
 * Created on Mar 22, 2016 at 8:10:52 PM
 *
 * @author Jack
 */

namespace jacknoordhuis\antihacks;

use pocketmine\event\Listener;
use pocketmine\entity\Effect;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerKickEvent;

class EventListener implements Listener {
        
        /** @var $plugin Main */
        private $plugin;
        
        /**
         * 
         * Construct a new EventListener class
         * 
         * @param Main $plugin
         */
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
                $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
        }
        
        /**
         * 
         * Get owning plugin instance
         * 
         * @return Main
         */
        public function getPlugin() {
                return $this->plugin;
        }
        
        /**
         * 
         * @param PlayerMoveEvent $event
         * 
         * @return null
         * 
         * @priority HIGHEST
         */
        public function onMove(PlayerMoveEvent $event) {
                $player = $event->getPlayer();
                if($event->isCancelled() or $player->hasPermission("antifly.exempt")or $player->isCreative() or $player->isSpectator() or $player->getAllowFlight() or $player->hasEffect(Effect::JUMP)) {
                        return;
                }  else {
                        if(($player->getInAirTicks() * 20) >= $this->plugin->getSettings()["in-air-threshold"]) {
                                $player->kick(Main::applyColor($this->plugin->getSettings()["messages"]["flying-kick"]), false);
                        } elseif(abs($event->getFrom()->y - $event->getTo()->y) >= $this->plugin->getSettings()["jump-blocks"]) {
                                $this->plugin->addTag($player);
                        }
                        if($this->plugin->checkTag($player)) {
                                $player->kick(Main::applyColor($this->plugin->getSettings()["messages"]["jumping-kick"]), false);
                        }
                }
                return;
        }
        
        /**
         * @param PlayerQuitEvent $event
         * 
         * @return null;
         */
        public function onQuit(PlayerQuitEvent $event) {
                $this->plugin->removeTag($event->getPlayer());
                return;
        }
        
        /**
         * @param PlayerKickEvent $event
         * 
         * @return null
         */
        public function onKick(PlayerKickEvent $event) {
                $this->plugin->removeTag($event->getPlayer());
                return;
        }
}