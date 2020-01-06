<?php

namespace yurisi;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerPreLoginEvent;

use yurisi\Task\Sendtask;

class main extends PluginBase implements Listener {

   private $plugin= "PISB";

   public function onEnable() {
	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	$this->getScheduler()->scheduleRepeatingTask(new Sendtask($this), 5);
	$this->getLogger()->info("§b".$this->plugin."を開きました");
   }

   public function Login(PlayerPreLoginEvent $event){
	$tag=$event->getPlayer()->namedtag;
	if ($tag->offsetExists($this->plugin)) {
		$tag->setInt($this->plugin, 0);
	}

   }
   public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {

	   switch ($command->getName()) {
		   case "joho":
			   if ($sender instanceof Player) {
				   $tag = $sender->namedtag;
				   if ($this->isOn($sender)===false) {
					   $tag->setInt($this->plugin, 0);
					   $sender->sendMessage("[".$this->plugin."]§aONにいたしました。");
					   return true;
				   } else {
					   $tag->setInt($this->plugin, 1);
					   $sender->sendMessage("[".$this->plugin."]§aOFFにしました。");
					   return true;
				   }
			   }
			   break;
	   }
   }

   public function isOn(Player$player){
	   $tag = $player->namedtag;
	   if ($tag->offsetExists($this->plugin)) {
		   if ($tag->getInt($this->plugin) == 0) {
			   return true;
		   } else {
			   return false;
		   }
	   }else{
	   	return true;
	   }
   }

   public function onDisable() {
		$this->getLogger()->info("§a".$this->plugin."を閉じました");
   }

}