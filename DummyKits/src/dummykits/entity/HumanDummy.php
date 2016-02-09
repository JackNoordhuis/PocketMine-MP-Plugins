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
                $this->namedtag->customName = new String("customName", $this->customName);
                $this->namedtag->customDescription = new String("customDescription", $this->customDescription);
                $this->namedtag->kits = new Enum("kits", $this->kits);
                $this->namedtag->kits->setTagType(NBT::TAG_Compound);
                $this->namedtag->commands = new Enum("commands", $this->commands);
                $this->namedtag->commands->setTagType(NBT::TAG_Compound);
                $this->namedtag->look = new Byte("look", ($this->look ? 1 : 0));
                $this->namedtag->knockback = new Byte("kockback", ($this->knockback ? 1 : 0));
        }
        
        protected function initEntity() {
                parent::initEntity();
                if(isset($this->namedtag->customName) and $this->namedtag->customName instanceof String) {
                        $this->customName = $this->namedtag["customName"];
                }
                if(isset($this->namedtag->customDescription) and $this->namedtag->customDescription instanceof String) {
                        $this->customDescription = $this->namedtag["customDescription"];
                }
                if(isset($this->namedtag->kits) and $this->namedtag->kits instanceof Compound) {
                        $this->kits = $this->namedtag["kits"];
                }
                if(isset($this->namedtag->look) and $this->namedtag->look instanceof Byte) {
                        $this->look = ($this->namedtag["customDescription"] === 1 ? true : false);
                }
                if(isset($this->namedtag->kncokback) and $this->namedtag->knockback instanceof Byte) {
                        $this->knockback = ($this->namedtag["knockback"] === 1 ? true : false);
                }
                $this->setNameTag(Main::centerString($this->customName, $this->customDescription) . "\n" . Main::centerString($this->customDescription, $this->customName));
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
