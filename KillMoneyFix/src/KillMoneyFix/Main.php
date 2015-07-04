<?php

/*
 * The MIT License
 *
 * Copyright 2015 Jack Noordhuis (CrazedMiner) CrazedMiner.weebly.com.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace KillMoneyFix;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;

class Main extends PluginBase implements Listener{
    
    public $economy = false;

    public function onLoad() {
        $this->getLogger()->info(TextFormat::BLUE . "Loading KillMoneyFix v1.0 By CrazedMiner....");
    }

    public function onEnable() {
        $this->config = $this->getConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if(!file_exists($this->getDataFolder() . "config.yml")) {
            @mkdir($this->getDataFolder());
             file_put_contents($this->getDataFolder() . "config.yml",$this->getResource("config.yml"));
        }
        if($this->config->get("Economy-Plugin") == "Economy") {
            if(is_dir($this->getServer()->getPluginPath()."EconomyAPI")) {
		$this->getLogger()->info(TextFormat::GREEN."KillMoneyFix v1.0 By CrazedMiner Enabled with Economy!");
		$this->economy = true;
            }else{
		$this->getLogger()->info(TextFormat::RED."KillMoneyFix could not be loaded, I can't find the Economy plugin");
		$this->economy = false;
            }
        }
        elseif($this->config->get("Economy-Plugin") == "PocketMoney") {
            if(is_dir($this->getServer()->getPluginPath()."PocketMoney")) {
		$this->getLogger()->info(TextFormat::GREEN."KillMoneyFix v1.0 By CrazedMiner Enabled with PocketMoney!");
		$this->economy = true;
            }else{
		$this->getLogger()->info(TextFormat::RED."KillMoneyFix could not be loaded, I can't find the PocketMoney plugin");
		$this->economy = false;
            }
        }
    }

    public function onDisable() {
        $this->getLogger()->info(TextFormat::GREEN . "KillMoneyFix v1.0 By CrazedMiner Disabled!");
    }
    
    public function onDeath(PlayerDeathEvent $event) {
        $cause = $event->getEntity()->getLastDamageCause();
        if($cause instanceof EntityDamageByEntityEvent) {
            $player = $event->getEntity();
            $killer = $event->getEntity()->getLastDamageCause()->getDamager();
            if($killer instanceof Player) {
                $imessage = str_replace("@coins", $this->config->get("Money"), $this->config->get("Message"));
                $message = str_replace("@player", $player->getName(), $imessage);
                if($this->config->get("Economy-Plugin") == "Economy") {
                    $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->addMoney($killer->getName(), $this->config->get("Money"));
                    $killer->sendMessage($message);
                }
                elseif($this->config->get("Economy-Plugin") == "PocketMoney") {
                    $this->getServer()->getPluginManager()->getPlugin("PocketMoney")->grantMoney($killer->getName(), $this->config->get("Money"));
                    $killer->sendMessage($message);
                }
            }
        }
    }

}
