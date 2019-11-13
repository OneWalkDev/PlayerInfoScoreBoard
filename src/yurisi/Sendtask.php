<?php

namespace yurisi;


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

class Sendtask extends Task{

	public function __construct(Main $main){
		$this->Main = $main;
	}

	public function onRun($tick){
		foreach($this->Main->getServer()->getInstance()->getOnlinePlayers() as $player) {
			$name = $player->getName();

			if($this->Main->joho[$name]!=1) {

				$pk = new RemoveObjectivePacket();
				$pk->objectiveName = "sidebar";
				$player->sendDataPacket($pk);

				$pk = new SetDisplayObjectivePacket();
				$pk->displaySlot = "sidebar";
				$pk->objectiveName = "sidebar";
				$pk->displayName = "§a§l{$name}";
				$pk->criteriaName = "dummy";
				$pk->sortOrder = 0;
				$player->sendDataPacket($pk);

				$mymoney = EconomyAPI::getInstance()->myMoney($name);
				$x = $player->getfloorX();
				$y = $player->getfloorY();
				$z = $player->getfloorZ();
				$world = $player->getLevel()->getName();
				$onlp = count($player->getServer()->getOnlinePlayers());
				$fullp = $player->getServer()->getMaxPlayers();
				$item = $player->getInventory()->getItemInHand();
				$id = $item->getId();
				$damage = $item->getDamage();
				$time = date("G時i分s秒");
				$ping = $player->getPing();


				$entry = new ScorePacketEntry();
				$entry->objectiveName = "sidebar";
				$entry->type = $entry::TYPE_FAKE_PLAYER;
				$entry->customName = "§e§l所持金: {$mymoney}￥";
				$entry->score = 1;
				$entry->scoreboardId = 00001;

				$pk = new SetScorePacket();
				$pk->type = $pk::TYPE_CHANGE;
				$pk->entries[] = $entry;
				$player->sendDataPacket($pk);

				$entry = new ScorePacketEntry();
				$entry->objectiveName = "sidebar";
				$entry->type = $entry::TYPE_FAKE_PLAYER;
				$entry->customName = "§b§l座標: x:{$x} y:{$y} z:{$z}";
				$entry->score = 2;
				$entry->scoreboardId = 00002;

				$pk = new SetScorePacket();
				$pk->type = $pk::TYPE_CHANGE;
				$pk->entries[] = $entry;
				$player->sendDataPacket($pk);

				$entry = new ScorePacketEntry();
				$entry->objectiveName = "sidebar";
				$entry->type = $entry::TYPE_FAKE_PLAYER;
				$entry->customName = "§b§lワールド: {$world}";
				$entry->score = 3;
				$entry->scoreboardId = 00003;

				$pk = new SetScorePacket();
				$pk->type = $pk::TYPE_CHANGE;
				$pk->entries[] = $entry;
				$player->sendDataPacket($pk);

				$entry = new ScorePacketEntry();
				$entry->objectiveName = "sidebar";
				$entry->type = $entry::TYPE_FAKE_PLAYER;
				$entry->customName = "§c§l現在時刻: {$time}";
				$entry->score = 4;
				$entry->scoreboardId = 00004;

				$pk = new SetScorePacket();
				$pk->type = $pk::TYPE_CHANGE;
				$pk->entries[] = $entry;
				$player->sendDataPacket($pk);

				$entry = new ScorePacketEntry();
				$entry->objectiveName = "sidebar";
				$entry->type = $entry::TYPE_FAKE_PLAYER;
				$entry->customName = "§6§l持ってるid: {$id}:{$damage}";
				$entry->score = 5;
				$entry->scoreboardId = 00005;

				$pk = new SetScorePacket();
				$pk->type = $pk::TYPE_CHANGE;
				$pk->entries[] = $entry;
				$player->sendDataPacket($pk);

				$entry = new ScorePacketEntry();
				$entry->objectiveName = "sidebar";
				$entry->type = $entry::TYPE_FAKE_PLAYER;
				$entry->customName = "§6§lオンライン人数: {$onlp}/{$fullp}";
				$entry->score = 6;
				$entry->scoreboardId = 00006;

				$pk = new SetScorePacket();
				$pk->type = $pk::TYPE_CHANGE;
				$pk->entries[] = $entry;
				$player->sendDataPacket($pk);
			}else{

				$pk = new RemoveObjectivePacket();
				$pk->objectiveName = "sidebar";
				$player->sendDataPacket($pk);
			}

		}
	}
}