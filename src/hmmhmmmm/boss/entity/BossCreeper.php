<?php

namespace hmmhmmmm\boss\entity;

use revivalpmmp\pureentities\entity\monster\walking\Creeper;
use slapper\entities\SlapperHuman;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\BossManager;

use pocketmine\Player;
use pocketmine\nbt\tag\StringTag;
use pocketmine\entity\Creature;

class BossCreeper extends Creeper{
   public $health = 20;
   
   public function initEntity() : void{
      parent::initEntity();
      if($this->namedtag->hasTag("Boss".$this->getName(), StringTag::class)){
         $name = $this->namedtag->getString("Boss".$this->getName());
         $this->setHealth(BossData::getHealth($name));
         $this->health = BossData::getHealth($name);
         $this->speed = BossData::getSpeed($name);
         $this->setMinDamage(BossData::getMinDamage($name));
         $this->setMaxDamage(BossData::getMaxDamage($name));
         $this->setScale(BossData::getScale($name));
      }
   }
   
   public function explode(){
      parent::explode();
      if($this->namedtag->hasTag("Boss".$this->getName(), StringTag::class)){
         if(BossData::isBoss($this->namedtag->getString("Boss".$this->getName()))){
            BossData::setRespawnTime($this->namedtag->getString("Boss".$this->getName()), 2);
         }
      }
   }
   
   public function getMaxHealth(): int{
      if($this->namedtag->hasTag("Boss".$this->getName(), StringTag::class)){
         if(BossData::isBoss($this->namedtag->getString("Boss".$this->getName()))){
            return BossData::getHealth($this->namedtag->getString("Boss".$this->getName()));
         }else{
            return $this->health;
         }
      }else{
         return $this->health;
      }
   }
   
   public function targetOption(Creature $creature, float $distance) : bool{
      if(!($creature instanceof SlapperHuman)){
         return parent::targetOption($creature, $distance);
      }
      return false;
   }
   
   public function entityBaseTick(int $tickDiff = 1) : bool{
      $hasUpdate = parent::entityBaseTick($tickDiff);
      if($this->namedtag->hasTag("Boss".$this->getName(), StringTag::class)){
         $name = $this->namedtag->getString("Boss".$this->getName());
         if(BossData::isBoss($name)){
            $this->setNameTag($name." §c(".$this->getHealth()."/".$this->getMaxHealth().")");
            $this->setNameTagAlwaysVisible(true);
            $this->setNameTagVisible(true);
         }
      }
      return $hasUpdate;
   }
   
}