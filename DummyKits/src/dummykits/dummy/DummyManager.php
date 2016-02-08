<?php

namespace dummykits\dummy;

use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\entity\Entity;
use pocketmine\level\Level;

use pocketmine\nbt\tag\Byte;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Double;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\Float;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Short;
use pocketmine\nbt\tag\String;

use dummykits\Main;
use dummykits\entity\HumanDummy;

class DummyManager {
        
        private $plugin = null;
        
        public $path = "";
        
        public $dummyData = [];
        
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
                $this->path = $plugin->getDataFolder() . DIRECTORY_SEPARATOR . "Dummys" . DIRECTORY_SEPARATOR;
                $this->checkDummys();
        }
        
        public function checkDummys() {
                if(!is_dir($this->path)) @mkdir($this->path);
                if(!is_dir($this->path . "data" . DIRECTORY_SEPARATOR)) @mkdir($this->path . "data" . DIRECTORY_SEPARATOR);
                foreach(scandir($this->path) as $dummy) {
                        $parts = explode(".", $dummy);
                        if($parts[1] !== "yml")
                                continue;
                        $data = (new Config($this->path . $dummy, Config::YAML))->getAll();
                        if(!$this->isSpawned($data["name"])) {
                                $this->spawn($data["name"], $data["description"], $data["level"], self::parsePos($data["level"]), $data["yaw"], $data["pitch"], self::parseItem($data["hand-item"]), self::parseArmor($data["armor"]), (bool) $data["look"], $data["kits"], $data["commands"]);
                        } else {
                                continue;
                        }
                }
        }
        
        public function spawn($name, $description, $level, Vector3 $pos, $yaw, $pitch, Item $handItem, array $armor, $look, array $kits, array $commands) {
                $nbt = new Compound("", [
                    "Pos" => new Enum("Pos", [
                        new Double("0", $pos->x),
                        new Double("1", $pos->y),
                        new Double("2", $pos->z)
                    ]),
                    
                    "Motion" => new Enum("Motion", [
                        new Double("", 0),
                        new Double("", 0),
                        new Double("", 0)
                    ]),
                    
                    "Rotation" => new Enum("Rotation", [
                        new Float("", $yaw),
                        new Float("", $pitch)
                    ]),
                    
                    "Health" => new Short("Health", 20),
                    
                    "customName" => new String("customName", $name),
                    "customDescription" => new String("customDescription", $description),
                    "kits" => new Enum("kits", $kits),
                    "commands" => new Enum("commands", $commands),
                    "look" => new Byte("look", ($look ? 1 : 0))
                    
                ]);
                $nbt->kits->setTagType(NBT::TAG_Compound);
                $nbt->commands->setTagType(NBT::TAG_Compound);
                
                if(($level = $this->plugin->getServer()->getLevelByName($level)) instanceof Level) {
                        $dummy = Entity::createEntity("HumanDummy", $level->getChunk($pos->x >> 4, $pos->z >> 4), $nbt);
                        if($dummy instanceof HumanDummy) {
                                $inv = $dummy->getInventory();
                                $inv->setItemInHand($handItem);
                                $inv->setHelmet($armor[0]);
                                $inv->setChestplate($armor[1]);
                                $inv->setLeggings($armor[2]);
                                $inv->setBoots($armor[3]);
                                $dummy->spawnToAll();
                                $this->writeSpawned($name);
                        } else {
                                $dummy->kill();
                        }
                } else {
                        return;
                }
        }
        
        public function writeSpawned($name) {
                $file = fopen($this->path . "data" . DIRECTORY_SEPARATOR . Main::removeColors($name) . ".npc", "w");
                fwrite($file, "name: " . Main::removeColors($name) . "\n\r\n");
                fclose($file);
        }
        
        public function isSpawned($name) {
                return is_file($this->path . "data" . DIRECTORY_SEPARATOR . Main::removeColors($name) . ".npc");
        }
        
        public static function parsePos($string) {
                $temp = explode(",", str_replace(" ", "", $string));
                if(isset($temp[2])) {
                        return new Vector3($temp[0], $temp[1], $temp[2]);
                } else {
                        return;
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
        
        public static function parseItem($string) {
                $temp = explode(",", str_replace(" ", "", $string));
                if(isset($temp[2])) {
                        return Item::get($temp[0], $temp[1], $temp[2]);
                } else {
                        return;
                }
        }
        
}
