<?php

namespace jacknoordhuis\dummykits\dummy;

use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\entity\Entity;
use pocketmine\level\Level;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\EnumTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;

use jacknoordhuis\dummykits\Main;
use jacknoordhuis\dummykits\entity\HumanDummy;

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
                if(!is_dir($this->path))  {
                        @mkdir($this->path);
                        $this->plugin->saveResource("Dummys" . DIRECTORY_SEPARATOR . "Default.yml");
                }
                if(!is_dir($this->path . "data" . DIRECTORY_SEPARATOR)) @mkdir($this->path . "data" . DIRECTORY_SEPARATOR);
                
                foreach(scandir($this->path) as $dummy) {
                        $parts = explode(".", $dummy);
                        if(isset($parts[1]) and $parts[1] === "yml") {
                                $data = (new Config($this->path . $dummy, Config::YAML))->getAll();
                                if(!$this->isSpawned($data["name"])) {
                                        $this->spawn($data["name"], $data["description"], $data["level"], Main::parsePos($data["pos"]), $data["yaw"], $data["pitch"], Main::parseItem($data["hand-item"]), Main::parseArmor($data["armor"]), (bool) $data["look"], (bool) $data["knockback"], $data["kits"], $data["commands"]);
                                } else {
                                        continue;
                                }
                        } else {
                                continue;
                        }
                }
        }

        public function spawn($name, $description, $level, Vector3 $pos, $yaw, $pitch, Item $handItem, array $armor, $look = true, $knockback = false, array $kits = [], array $commands = []) {
                $nbt = new CompoundTag;
                
                $nbt->Pos = new EnumTag("Pos", [
                    new DoubleTag("", $pos->x),
                    new DoubleTag("", $pos->y),
                    new DoubleTag("", $pos->z)
                ]);
                
                $nbt->Motion = new EnumTag("Motion", [
                    new DoubleTag("", 0),
                    new DoubleTag("", 0),
                    new DoubleTag("", 0)
                ]);
                
                $nbt->Rotation = new EnumTag("Rotation", [
                    new FloatTag("", $yaw),
                    new FloatTag("", $pitch)
                ]);
                
                $nbt->Health = new ShortTag("Health", 1);
                
                $nbt->DummyData = new CompoundTag("DummyData", [
                    "Name" => new StringTag("Name", $name),
                    "Description" => new StringTag("Description", $description),
                    "Kits" => new EnumTag("Kits", Main::array2StringTag($kits)),
                    "Commands" => new EnumTag("Commands", Main::array2StringTag($commands)),
                    "Look" => new ByteTag("Look", ($look ? 1 : 0)),
                    "Knockback" => new ByteTag("Knockback", ($knockback ? 1 : 0))
                ]);
                
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
        
}
