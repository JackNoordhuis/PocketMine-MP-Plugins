<?php

namespace jacknoordhuis\dummykits\entity;

use pocketmine\entity\Human;
use pocketmine\Player;

use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\network\protocol\MovePlayerPacket;

use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\Byte;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\Compound;

use jacknoordhuis\dummykits\Main;

class HumanDummy extends Human implements Dummy {
        
        public $customName = "";
        
        public $customDescription = "";
        
        public $kits = [];
        
        public $commands = [];
        
        public $move = true;
        
        public $knockback = true;
        
        public function setCustomName($name) {
                $this->customName = $name;
                $this->setNameTag(Main::translateColors(Main::centerString($this->customName, $this->customDescription) . "\n" . Main::centerString($this->customDescription, $this->customName)));
        }
        
        public function setCustomDescription($string) {
                $this->customDescription = $string;
                $this->setNameTag(Main::translateColors(Main::centerString($this->customName, $this->customDescription) . "\n" . Main::centerString($this->customDescription, $this->customName)));
        }
        
        public function addKit($string) {
                $this->kits[] = $string;
        }
        
        public function addCommand($string) {
                $this->commands[] = $string;
        }
        
        public function setMove($value = true) {
                $this->move = $value;
        }
        
        public function setKnockback($value = true) {
                $this->kockback = $value;
        }
        
        public function spawnTo(Player $player) {
                if($player !== $this and ! isset($this->hasSpawned[$player->getLoaderId()])) {
                        $this->hasSpawned[$player->getLoaderId()] = $player;

                        $this->server->updatePlayerListData($this->getUniqueId(), $this->getId(), "", $player->getSkinName(), $player->getSkinData(), [$player]);

                        $pk = new AddPlayerPacket();
                        $pk->uuid = $this->getUniqueId();
                        $pk->username = $this->getNameTag();
                        $pk->eid = $this->getId();
                        $pk->x = $this->x;
                        $pk->y = $this->y;
                        $pk->z = $this->z;
                        $pk->speedX = 0;
                        $pk->speedY = 0;
                        $pk->speedZ = 0;
                        $pk->yaw = $this->yaw;
                        $pk->pitch = $this->pitch;
                        $pk->item = $this->getInventory()->getItemInHand();
                        $pk->metadata = $this->dataProperties;
                        $player->dataPacket($pk);

                        $this->inventory->sendArmorContents($player);
                }
        }
        
        public function saveNBT() {
                parent::saveNBT();
                $this->namedtag->DummyData = new Compound("DummyData", [
                    "Name" => new StringTag("Name", $this->customName),
                    "Description" => new StringTag("Description", $this->customDescription),
                    "Kits" => new EnumTag("Kits", Main::array2StringTag($this->kits)),
                    "Commands" => new EnumTag("Commands", Main::array2StringTag($this->commands)),
                    "Look" => new ByteTag("Look", ($this->move ? 1 : 0)),
                    "Knockback" => new ByteTag("Knockback", ($this->knockback ? 1 : 0))
                ]);
        }
        
        protected function initEntity() {
                parent::initEntity();
                if(isset($this->namedtag->DummyData)) {
                        if(isset($this->namedtag->DummyData["Name"])) {
                                $this->setCustomName($this->namedtag->DummyData["Name"]);
                        }
                        if(isset($this->namedtag->DummyData["Description"])) {
                                $this->setCustomDescription($this->namedtag->DummyData["Description"]);
                        }
                        if(isset($this->namedtag->DummyData["Kits"])) {
                                foreach($this->namedtag->DummyData["Kits"]->getValue() as $kit) {
                                        $this->addKit($kit);
                                }
                        }
                        if(isset($this->namedtag->DummyData["Commands"])) {
                                foreach($this->namedtag->DummyData["Commands"]->getValue() as $cmd) {
                                        $this->addCommand($cmd);
                                }
                        }
                        if(isset($this->namedtag->DummyData["Look"])) {
                                $this->setMove((bool) $this->namedtag->DummyData["Look"]);
                        }
                        if(isset($this->namedtag->DummyData["Knockback"])) {
                                $this->setKnockback((bool) $this->namedtag->DummyData["Knockback"]);
                        }
                } else {
                        $this->kill();
                }
                $this->setNameTag(Main::translateColors(Main::centerString($this->customName, $this->customDescription) . "\n" . Main::centerString($this->customDescription, $this->customName)));
        }
        
        public function look(Player $player) {
                $x = $this->x - $player->x;
                $y = $this->y - $player->y;
                $z = $this->z - $player->z;
                $yaw = asin($x / sqrt($x * $x + $z * $z)) / 3.14 * 180;
                $pitch = round(asin($y / sqrt($x * $x + $z * $z + $y * $y)) / 3.14 * 180);
                if($z > 0) $yaw = -$yaw + 180;
                
                $pk = new MovePlayerPacket();
                    $pk->eid = $this->id;
                    $pk->x = $this->x;
                    $pk->y = $this->y;
                    $pk->z = $this->z;
                    $pk->bodyYaw = $yaw;
                    $pk->pitch = $pitch;
                    $pk->yaw = $yaw;
                    $pk->mode = 0;
                $player->dataPacket($pk);
        }
        
}
