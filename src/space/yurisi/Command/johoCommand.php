<?php
declare(strict_types=1);

namespace space\yurisi\Command;

use pocketmine\Player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use space\yurisi\PlayerInfoScoreBoard;

class johoCommand extends Command {

	public function __construct() {
		parent::__construct("joho", "情報欄のon/off", "/joho");
	}

	public function execute(CommandSender $sender, string $label, array $args) {
		if (!$sender instanceof Player) return false;
		$tag = $sender->namedtag;
		$msg=["ON","OFF"];
		PlayerInfoScoreBoard::getInstance()->isOn($sender)?$flag=1:$flag=0;
		$tag->setInt(PlayerInfoScoreBoard::getInstance()->getName(), $flag);
		$sender->sendMessage("[" . PlayerInfoScoreBoard::getInstance()->getName() . "]§a{$msg[$flag]}にしました。");
		return true;
	}

}