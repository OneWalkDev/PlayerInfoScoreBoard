<?php

namespace yurisi\Command;

use pocketmine\Player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use yurisi\main;

class johoCommand extends Command {

	public function __construct(main $main) {
		$this->main = $main;
		parent::__construct("joho", "情報欄のon/off", "/joho");
	}

	public function execute(CommandSender $sender, string $label, array $args) {
		if ($sender instanceof Player) {
			$tag = $sender->namedtag;
			if ($this->main->isOn($sender) === false) {
				$tag->setInt($this->main->plugin, 0);
				$sender->sendMessage("[" . $this->main->plugin . "]§aONにいたしました。");
				return true;
			} else {
				$tag->setInt($this->main->plugin, 1);
				$sender->sendMessage("[" . $this->main->plugin . "]§aOFFにしました。");
				return true;
			}
		}
	}
}