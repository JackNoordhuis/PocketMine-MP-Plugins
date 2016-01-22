<?php

/*
 * PositionTeller plugin for PocketMine-MP
 * Copyright (C) 2014 Jack Noordhuis (CrazedMiner) 
 * <https://github.com/CrazedMiner/PocketMine-MP-Plugins/tree/master/PositionTeller>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

namespace positionteller;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use pocketmine\Player;

use positionteller\commands\TogglePos;
use positionteller\tasks\ShowPos;
use positionteller\EventListener;

class Main extends PluginBase {
    
    /** @var array Settings.yml */
    private $settings = [];
    
    /** @var array Active Players  */
    public $active = array();

    public function onEnable() {
        $this->saveResource("Settings.yml");
        $this->settings = new Config($this->getDataFolder() . "Settings.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getCommand("togglepos")->setExecutor(new TogglePos($this));
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new ShowPos($this), 4);
        $this->getLogger()->info(TF::AQUA . "PositionTeller v1.0" . TF::GREEN . " by " . TF::YELLOW . "Jack Noordhuis" . TF::GREEN . ", Loaded successfully!");
    }

    public function onDisable() {
        unset($this->settings);
        unset($this->active);
    }
    
    public function addActive(Player $player) {
        $this->active[spl_object_hash($player)] = $player;
    }
    
    public function isActive(Player $player) {
        return isset($this->active[spl_object_hash($player)]);
    }
    
    public function removeActive(Player $player) {
        unset($this->active[spl_object_hash($player)]);
    }
    
    public function getConfigValue($nested) {
        return $this->settings->getNested("settings." . $nested);
    }
    
    public static function translateColors($string, $symbol = "&") {
        $string = str_replace($symbol."0", TF::BLACK, $string);
        $string = str_replace($symbol."1", TF::DARK_BLUE, $string);
        $string = str_replace($symbol."2", TF::DARK_GREEN, $string);
        $string = str_replace($symbol."3", TF::DARK_AQUA, $string);
        $string = str_replace($symbol."4", TF::DARK_RED, $string);
        $string = str_replace($symbol."5", TF::DARK_PURPLE, $string);
        $string = str_replace($symbol."6", TF::GOLD, $string);
        $string = str_replace($symbol."7", TF::GRAY, $string);
        $string = str_replace($symbol."8", TF::DARK_GRAY, $string);
        $string = str_replace($symbol."9", TF::BLUE, $string);
        $string = str_replace($symbol."a", TF::GREEN, $string);
        $string = str_replace($symbol."b", TF::AQUA, $string);
        $string = str_replace($symbol."c", TF::RED, $string);
        $string = str_replace($symbol."d", TF::LIGHT_PURPLE, $string);
        $string = str_replace($symbol."e", TF::YELLOW, $string);
        $string = str_replace($symbol."f", TF::WHITE, $string);

        $string = str_replace($symbol."k", TF::OBFUSCATED, $string);
        $string = str_replace($symbol."l", TF::BOLD, $string);
        $string = str_replace($symbol."m", TF::STRIKETHROUGH, $string);
        $string = str_replace($symbol."n", TF::UNDERLINE, $string);
        $string = str_replace($symbol."o", TF::ITALIC, $string);
        $string = str_replace($symbol."r", TF::RESET, $string);

        return $string;
    }

}
