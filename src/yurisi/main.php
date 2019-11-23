<?php

namespace yurisi;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;
use pocketmine\scheduler\Task;

use pocketmine\event\player\PlayerLoginEvent;

use metowa1227\moneysystem\api\core\API;

class main extends PluginBase implements Listener {

   public $joho;
	
   private $plugin;
	
   public function onEnable() {
	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	$this->plugin = "PISB";
	$this->getLogger()->info("§b".$this->plugin."を開きました");
	$this->getScheduler()->scheduleRepeatingTask(new Sendtask($this), 5);
   }
   public function Login(PlayerLoginEvent $event){
	$name=$event->getPlayer()->getName();
	$this->joho[$name]=0;

   }
   public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
	$name = $sender->getPlayer()->getName();
	switch($command->getName()){
		case "joho":
			if($sender instanceof Player){
				if(!$this->joho[$name]==0){
					$this->joho[$name]=0;
					$sender->sendMessage("[PISB]§aONにいたしました。");
					return false;
				}else{
					$this->joho[$name]=1;
					$sender->sendMessage("[PISB]§aOFFにしました。");
					return false;
				}
			}
		break;
	}
   }

   public function onDisable() {
		$this->getLogger()->info("§a".$this->plugin."を閉じました");
   }
}
