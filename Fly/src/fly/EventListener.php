<?php

namespace fly;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat as TF;

use fly\Main;

class EventListener implements Listener {
    
    private $plugin;
    
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }
    
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if($this->plugin->hasFlyingSession($player)) {
            $this->plugin->removeFlyingSession($player);
        }
        $this->plugin->addFlyingSession($player);
    }
    
    public function onDamage(EntityDamageEvent $event) {
        $victim = $event->getEntity();
        if($victim instanceof Player) {
            if(!$event->isCancelled()) {
                if($this->plugin->getFlyingSession($victim)->getFlying()) {
                        $this->plugin->getFlyingSession($victim)->setFlying(false);
                        $victim->sendMessage(TF::GOLD . "You are no longer in fly mode!");
                }
            }
        }
    }
    
    public function onKick(PlayerKickEvent $event) {
        $player = $event->getPlayer();
        if($this->plugin->hasFlyingSession($player)) {
            $this->plugin->removeFlyingSession($player);
        }
    }
    
    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        if($this->plugin->hasFlyingSession($player)) {
            $this->plugin->removeFlyingSession($player);
        }
    }
    
}