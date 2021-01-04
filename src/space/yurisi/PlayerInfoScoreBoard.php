<?php
declare(strict_types=1);

namespace space\yurisi;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;

use space\yurisi\Task\Sendtask;
use space\yurisi\Command\johoCommand;

class playerInfoScoreBoard extends PluginBase{

    private static $main;

	public function onEnable() {
	$this->getScheduler()->scheduleRepeatingTask(new Sendtask(), 5);
	$this->getServer()->getCommandMap()->register($this->getName(), new johoCommand());
	self::$main=$this;
   }

   public static function getInstance():self {
	   return self::$main;
   }

   public function isOn(Player $player) {
	   $tag = $player->namedtag;
	   if ($tag->offsetExists($this->getName())) if (!$tag->getInt($this->getName()) == 0) return false;
	   return true;
   }
}