<?php

namespace jacknoordhuis\dummykits\kit;

use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\entity\Effect;
use pocketmine\Player;

use jacknoordhuis\dummykits\Main;

class KitManager {

        private $plugin = null;
        
        public $path = "";
        
        public $kits = [];

        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
                $this->path = $plugin->getDataFolder() . DIRECTORY_SEPARATOR . "Kits" . DIRECTORY_SEPARATOR;
                $this->loadKits();
        }

        public function loadKits() {
                if(!is_dir($this->path)) {
                        @mkdir($this->path);
                        $this->plugin->saveResource("Kits" . DIRECTORY_SEPARATOR . "Default.yml");
                }

                foreach(scandir($this->path) as $kit) {
                        $parts = explode(".", $kit);
                        if(isset($parts[1]) and $parts[1] === "yml") {
                                $data = (new Config($this->path . $kit, Config::YAML))->getAll();
                                $this->registerKit((string) $data["name"], Main::parseArmor($data["armor"]), Main::parseItems($data["items"]), Main::parseEffects($data["effects"]), (bool) $data["clear-inv"], (bool) $data["clear-effects"]);
                        } else {
                                continue;
                        }
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
                $player->sendMessage("You have recivied the " . $kit->getName() . " kit!");
        }

}
