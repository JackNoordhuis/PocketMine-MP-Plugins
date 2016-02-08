<?php

namespace dummykits\kit;

use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\entity\Effect;
use pocketmine\Player;

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
                if(!is_dir($this->path))
                        @mkdir($this->path);

                foreach(scandir($this->path) as $kit) {
                        $parts = explode(".", $kit);
                        if($parts[1] !== "yml")
                                continue;
                        $data = (new Config($this->path . $kit, Config::YAML))->getAll();
                        $this->registerKit((string) $data["name"], self::parseArmor($data["armor"]), self::parseItems($data["items"]), self::parseEffects($data["effects"]), (bool) $data["clear-inv"], (bool) $data["clear-effects"]);
                }
        }

        public function registerKit($name, array $armor, array $items, array $effects, $clearInv, $clearEffects) {
                $this->kits[strtolower($name)] = new Kit($name, $armor, $items, $effects, $clearInv, $clearEffects);
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
        
        public function applyKit(Player $player, Kit $kit) {
                if($kit->getClearInv()) $player->getInventory()->clearAll();
                if($kit->getClearEffects()) $player->removeAllEffects();
                $inv = $player->getInventory();
                $armor = $kit->getArmor();
                foreach($kit->getItems() as $item) {
                        $inv->addItem($item);
                }
                $inv->setHelmet($armor[0]);
                $inv->setChestplate($armor[1]);
                $inv->setLeggings($armor[2]);
                $inv->setBoots($armor[3]);
                foreach($kit->getEffects() as $effect) {
                        $player->addEffect($effect);
                }
        }

        public static function parseArmor($string) {
                $temp = explode(",", str_replace(" ", "", $string));
                if(isset($temp[3])) {
                        return [Item::get($temp[0]), Item::get($temp[1]), Item::get($temp[2]), Item::get($temp[3])];
                } else {
                        return [];
                }
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
