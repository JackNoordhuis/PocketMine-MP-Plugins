<?php

/**
 * Main class
 * 
 * Created on Mar 22, 2016 at 8:09:24 PM
 *
 * @author Jack
 */

namespace antihacks;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class Main extends PluginBase {
        
        /** @var $listener EventListener */
        private $listener;
        
        /** @var $settings Config */
        public $settings;
        
        /** @var $tags array */
        public $tags = [];
        
        const VESION_STRING = "1.0.0";
        
        public function onEnable() {
                $this->loadConfigs();
                $this->setListener();
                $this->getLogger()->info(self::applyColor("&eAntiHacks &b" . self::VERSION_STRING . " &ahas been enabled!"));
        }
        
        /**
         * Save and load configs
         */
        public function loadConfigs() {
                $this->saveResource("Settings.yml");
                $this->settings = new Config($this->getDataFolder() . "Settings.yml", Config::YAML);
        }
        
        /**
         * Set the event listener
         * 
         * @return null
         */
        public function setListener() {
                if(!$this->listener instanceof EventListener) {
                        $this->listener = new EventListener($this);
                }
                return;
        }
        
        /**
         * Returns the event listener
         * 
         * @return EventListener|null
         */
        public function getListener() {
                return $this->listener;
        }
        
        /**
         * Returns an array of the settings data
         * 
         * @return array
         */
        public function getSettings() {
                return $this->settings->getAll();
        }
        
        /**
         * Updates a players jump tag
         * 
         * @param string|Player $player
         */
        public function addTag($player) {
                if($player instanceof Player) {
                        $player = $player->getName();
                }
                
                $name = strtolower($player);
                if(isset($this->tags[$name])) {
                        $this->tags[$name]++;
                } else {
                        $this->tags[$name] = 1;
                }
        }
        
        /**
         * Checks a players jump tag to see if they should be kicked
         * 
         * @param string|Player $player
         * @return bool
         */
        public function checkTag($player) {
                if($player instanceof Player) {
                        $player = $player->getName();
                }
                
                $name = strtolower($player);
                if(isset($this->tags[$name])) {
                        if($this->tags[$player] >= $this->getSettings()["jump-tag-threshold"]) {
                                return true;
                        }
                }
                return false;
        }
        
        /**
         * Removes a players jump tag
         * 
         * @param string|Player $player
         */
        public function removeTag($player) {
                if($player instanceof Player) {
                        $player = $player->getName();
                }
                
                $name = strtolower($player);
                if(isset($this->tags[$name])) {
                        unset($this->tags[$name]);
                }
        }
        
        /**
         * Applys Minecraft color codes to a string using a different symbol
         * 
         * @param string $string
         * @param string $symbol
         * 
         * @return string
         */
        public static function applyColor($string, $symbol = "&") {
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