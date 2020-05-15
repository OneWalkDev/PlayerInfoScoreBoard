<?php

namespace yurisi\Task;


use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;
use pocketmine\scheduler\Task;

use pocketmine\level\Level;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;

use onebone\economyapi\EconomyAPI;
use Deceitya\MiningLevel\MiningLevelAPI;

use yurisi\main;
use yurisi\Data\PacketData;

class Sendtask extends Task implements PacketData{

	public function __construct(main $main){
		$this->Main = $main;
	}

	public function onRun($tick){
		foreach($this->Main->getServer()->getInstance()->getOnlinePlayers() as $player) {
			$name = $player->getName();
			$level = MiningLevelAPI::getInstance()->getLevel($player);//レベルを収得
			$exp = MiningLevelAPI::getInstance()->getExp($player);//EXP(経験値)を収得
			$nextexp =  MiningLevelAPI::getInstance()->getLevelUpExp($player);//次の経験値を収得
			$expmath = $nextexp - $exp;//次の残りの経験値を収得
			if($this->Main->isOn($player)) {
				$this->RemoveData($player);
				$this->setupData($player);
				$this->sendData($player,"§e所持金: ".EconomyAPI::getInstance()->getMonetaryUnit().EconomyAPI::getInstance()->myMoney($name),1);
				$this->sendData($player,"§b座標: ".$player->getfloorX().",".$player->getfloorY().",".$player->getfloorZ(),2);
				$this->sendData($player,"§bワールド: ".$player->getLevel()->getName(),3);
				$this->sendData($player,"§c現在時刻: ".date("G時i分s秒"),4);
				$this->sendData($player,"§6持ってるid: ".$player->getInventory()->getItemInHand()->getId().":".$player->getInventory()->getItemInHand()->getDamage(),5);
				$this->sendData($player,"§6オンライン人数: ".count($player->getServer()->getOnlinePlayers())."/".$player->getServer()->getMaxPlayers(),6);
				$this->sendData($player,"§e現在のレベル: ".$level,7);
				$this->sendData($player,"§e次のレベルまで: ".$expmath,8);
				//$this->sendData($player,"§e次のレベルまで: ".$exp."/".$nextexp,8); //(1/10のように表示させたい場合上のコードを消し、このコードに置き換える。)
			}else{
				$this->RemoveData($player);
			}

		}
	}

	function setupData(Player $player){
		$pk = new SetDisplayObjectivePacket();
		$pk->displaySlot = PacketData::D_S;
		$pk->objectiveName = PacketData::D_S;
		$pk->displayName = "§a".$player->getName();
		$pk->criteriaName = PacketData::C_N;
		$pk->sortOrder = 0;
		$player->sendDataPacket($pk);

	}

	function sendData(Player $player,String $data,Int $id){
		$entry = new ScorePacketEntry();
		$entry->objectiveName = PacketData::D_S;
		$entry->type = $entry::TYPE_FAKE_PLAYER;
		$entry->customName = $data;
		$entry->score = $id;
		$entry->scoreboardId = $id+11;

		$pk = new SetScorePacket();
		$pk->type = $pk::TYPE_CHANGE;
		$pk->entries[] = $entry;
		$player->sendDataPacket($pk);
	}

	function RemoveData(Player $player){
		$pk = new RemoveObjectivePacket();
		$pk->objectiveName = PacketData::D_S;
		$player->sendDataPacket($pk);
	}
}