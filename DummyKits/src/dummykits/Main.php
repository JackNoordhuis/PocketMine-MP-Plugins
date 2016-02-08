<?php

namespace dummykits;

use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;
use pocketmine\utils\TextFormat as TF;

use dummykits\entity\HumanDummy;
use dummykits\kit\KitManager;
use dummykits\dummy\DummyManager;

class Main extends PluginBase {
        
        public $kitManager = null;
        
        public $dummyManager = null;
        
        public function onEnable() {
                $this->registerEntities();
                $this->setKitManager();
                $this->setDummyManager();
        }
        
        public function registerEntities() {
                Entity::registerEntity(HumanDummy::class, true);
        }
        
        public function setKitManager() {
                if(isset($this->kitManager) and $this->kitManager instanceof KitManager) return;
                $this->kitManager = new KitManager($this);
        }
        
        public function setDummyManager() {
                if(isset($this->dummyManager) and $this->dummyManager instanceof DummyManager) return;
                $this->dummyManager = new DummyManager($this);
        }
        
        public static function centerString($string, $around) {
                if(strlen($string) >= strlen($around)) {
                        return $around;
                }

                $times = floor((strlen($around) - strlen($string)) / 2);
                return str_repeat(" ", ($times > 0 ? $times : 0)) . $string;
        }
        
        public static function translateColors($string, $symbol = "&") {
                $string = str_replace($symbol . "0", TF::BLACK, $string);
                $string = str_replace($symbol . "1", TF::DARK_BLUE, $string);
                $string = str_replace($symbol . "2", TF::DARK_GREEN, $string);
                $string = str_replace($symbol . "3", TF::DARK_AQUA, $string);
                $string = str_replace($symbol . "4", TF::DARK_RED, $string);
                $string = str_replace($symbol . "5", TF::DARK_PURPLE, $string);
                $string = str_replace($symbol . "6", TF::GOLD, $string);
                $string = str_replace($symbol . "7", TF::GRAY, $string);
                $string = str_replace($symbol . "8", TF::DARK_GRAY, $string);
                $string = str_replace($symbol . "9", TF::BLUE, $string);
                $string = str_replace($symbol . "a", TF::GREEN, $string);
                $string = str_replace($symbol . "b", TF::AQUA, $string);
                $string = str_replace($symbol . "c", TF::RED, $string);
                $string = str_replace($symbol . "d", TF::LIGHT_PURPLE, $string);
                $string = str_replace($symbol . "e", TF::YELLOW, $string);
                $string = str_replace($symbol . "f", TF::WHITE, $string);

                $string = str_replace($symbol . "k", TF::OBFUSCATED, $string);
                $string = str_replace($symbol . "l", TF::BOLD, $string);
                $string = str_replace($symbol . "m", TF::STRIKETHROUGH, $string);
                $string = str_replace($symbol . "n", TF::UNDERLINE, $string);
                $string = str_replace($symbol . "o", TF::ITALIC, $string);
                $string = str_replace($symbol . "r", TF::RESET, $string);
                
                return $string;
        }
        
        public static function removeColors($string, $symbol = "&") {
                $string = str_replace($symbol . "0", "", $string);
                $string = str_replace($symbol . "1", "", $string);
                $string = str_replace($symbol . "2", "", $string);
                $string = str_replace($symbol . "3", "", $string);
                $string = str_replace($symbol . "4", "", $string);
                $string = str_replace($symbol . "5", "", $string);
                $string = str_replace($symbol . "6", "", $string);
                $string = str_replace($symbol . "7", "", $string);
                $string = str_replace($symbol . "8", "", $string);
                $string = str_replace($symbol . "9", "", $string);
                $string = str_replace($symbol . "a", "", $string);
                $string = str_replace($symbol . "b", "", $string);
                $string = str_replace($symbol . "c", "", $string);
                $string = str_replace($symbol . "d", "", $string);
                $string = str_replace($symbol . "e", "", $string);
                $string = str_replace($symbol . "f", "", $string);

                $string = str_replace($symbol . "k", "", $string);
                $string = str_replace($symbol . "l", "", $string);
                $string = str_replace($symbol . "m", "", $string);
                $string = str_replace($symbol . "n", "", $string);
                $string = str_replace($symbol . "o", "", $string);
                $string = str_replace($symbol . "r", "", $string);
                
                $string = TF::clean($string);
                
                return $string;
        }
        
}
