<?php

namespace dummtkit\kit;

use pocketmine\item\Item;
use pocketmine\entity\Effect;

use dummykits\Main;
use dummykits\kit\Kit;

class KitManager {
        
        private $plugin = null;
        
        public $path = "";
        
        public $kits = [];
        
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
                $this->path = $plugin->getDataFolder() . DIRECTORY_SEPARATOR . "Kits" . DIRECTORY_SEPARATOR;
        }
        
        public function loadKits() {
                if(!is_dir($this->path)) @mkdir ($this->path);
                
                foreach(scandir($this->plugin->getDataFolder() . "Kits") as $kit) {
                        $parts = explode(".", $kit);
                        if($parts[1] !== "yml") continue;
                        $data = (new Config($this->path . $kit, Config::YAML))->getAll();
                        $this->registerKit($data["name"], self::parseItems($data["items"]), self::parseEffects($data["effects"]));
                }
        }
        
        public function registerKit($name, array $items, array $effects) {
                $this->kits[strtolower($name)] = new Kit($name, $items, $effects);
        }
        
        public function isKit($string) {
                return isset($this->kits[strtolower($string)]) and $this->kits[$string] instanceof Kit;
        }
        
        public function getKit($string) {
                if(!$this->isKit($string)) return;
                return $this->kits[strtolower($string)];
        }
        
        public function removeKit($string) {
                if(!$this->isKit($string)) return;
                unset($this->kits[strtolower($string)]);
        }
        
        public static function parseItems(array $strings) {
                $items = [];
                foreach($strings as $string) {
                        $temp = explode(",", str_replace(" ", "", $string));
                        if(isset($temp[2])) {
                                $items[] = Item::get($temp[0], $temp[1], $temp[2]);
                        } else {
                                continue;
                        }
                }
                return $items;
        }
        
        public static function parseEffects(array $strings) {
                $effects = [];
                foreach($strings as $string) {
                        $temp = explode(",", str_replace(" ", "", $string));
                        if(!isset($temp[3])) {
                                $effects[] = Effect::getEffectByName($temp[0])->setAmplifier($temp[1])->setDuration(20 * $temp[2]);
                        } else {
                                continue;
                        }
                }
                return $effects;
        }
        
}
