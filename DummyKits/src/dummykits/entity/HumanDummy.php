<?php

namespace dummykits\entity;

use pocketmine\entity\Human;
use pocketmine\Player;

use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\network\protocol\MovePlayerPacket;

use pocketmine\nbt\tag\String;
use pocketmine\nbt\tag\Byte;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Compound;

use dummykits\Main;
use dummykits\entity\Dummy;
use dummykits\dummy\DummyManager;

class HumanDummy extends Human implements Dummy {
        
        public $customName = "";
        
        public $customDescription = "";
        
        public $kits = [];
        
        public $commands = [];
        
        public $move = true;
        
        public $knockback = true;
        
        public function setCustomName($name) {
                $this->customName = $name;
        }
        
        public function setCustomDescription($string) {
                $this->customDescription = $string;
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
                if(isset($this->namedtag->DummyData) and $this->namedtag->DummyData instanceof Compound) {
                        $this->namedtag->DummyData["name"] = new String("name", $this->customName);
                        $this->namedtag->DummyData["description"] = new String("description", $this->customDescription);
                        $this->namedtag->DummyData["kits"] = new Compound("kits", DummyManager::array2Compound($this->kits));
                        $this->namedtag->DummyData["commands"] = new Compound("commands", DummyManager::array2Compound($this->commands));
                        $this->namedtag->DummyData["look"] = new Byte("look", ($this->move ? 1 : 0));
                        $this->namedtag->DummyData["knockback"] = new Byte("knockback", ($this->knockback ? 1 : 0));
                } else {
                        $this->kill();
                }
        }
        
        protected function initEntity() {
                parent::initEntity();
                if(isset($this->namedtag->DummyData) and $this->namedtag->DummyData instanceof Compound) {
                        if(isset($this->namedtag->DummyData["name"]) and $this->namedtag->DummyData["name"] instanceof String) {
                                $this->customName = $this->namedtag->DummyData["name"]->getValue();
                        }
                        if(isset($this->namedtag->DummyData["description"]) and $this->namedtag->DummyData["description"] instanceof String) {
                                $this->customDescription = $this->namedtag->DummyData["description"]->getValue();
                        }
                        if(isset($this->namedtag->DummyData["kits"]) and $this->namedtag->DummyData["kits"] instanceof String) {
                                foreach($this->namedtag->DummyData["kits"] as $kit) {
                                        $$this->kits[] = $kit->getValue();
                                }
                        }
                        if(isset($this->namedtag->DummyData["commands"]) and $this->namedtag->DummyData["commands"] instanceof String) {
                                foreach($this->namedtag->DummyData["commands"] as $cmd) {
                                        $$this->kits[] = $cmd->getValue();
                                }
                        }
                        if(isset($this->namedtag->DummyData["look"]) and $this->namedtag->DummyData["look"] instanceof Byte) {
                                $this->move = (bool) $this->namedtag->DummyData->getValue()["look"]->getValue();
                        }
                        if(isset($this->namedtag->DummyData["knockback"]) and $this->namedtag->DummyData["knockback"] instanceof Byte) {
                                $this->knockback = (bool) $this->namedtag->DummyData["knockback"]->getValue();
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
