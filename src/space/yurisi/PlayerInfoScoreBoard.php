<?php
declare(strict_types=1);

namespace space\yurisi;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use pocketmine\event\player\{
  PlayerJoinEvent,
  PlayerQuitEvent
};

use pocketmine\utils\Config;
use space\yurisi\Task\SendTask;
use space\yurisi\Command\johoCommand;

class playerInfoScoreBoard extends PluginBase implements Listener {

  private array $tasks;

  private Config $config;

  private array $data;

  public function onEnable(): void {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->getServer()->getCommandMap()->register($this->getName(), new johoCommand($this));
    $this->config = new Config($this->getDataFolder() . "player.yml", Config::YAML);
    $this->data = $this->config->getAll();
  }

  public function onJoin(PlayerJoinEvent $event) {
    if (!$this->isOn($event->getPlayer())) return;
    $this->setTask($event->getPlayer());
  }

  public function onQuit(PlayerQuitEvent $event) {
    $this->cancelTask($event->getPlayer());
  }

  public function setTask(Player $player) {
    $task = new SendTask($player);
    $this->getScheduler()->scheduleRepeatingTask($task, 5);
    $this->tasks[$player->getName()] = $task->getHandler();
  }

  public function cancelTask(Player $player) {
    if (!isset($this->tasks[$player->getName()])) return;
    $this->tasks[$player->getName()]->cancel();
    unset($this->tasks[$player->getName()]);
  }

  public function changeParam(Player $player) {
    if (!isset($this->data[$player->getName()]) or $this->data[$player->getName()]) {
      $this->data[$player->getName()] = false;
      $this->cancelTask($player);
      return;
    }
    $this->data[$player->getName()] = true;
    $this->setTask($player);
  }

  public function isOn(Player $player): bool {
    if (isset($this->data[$player->getName()])) {
      return $this->data[$player->getName()];
    }
    return true;
  }

  public function onDisable(): void {
    $this->config->setAll($this->data);
    try {
      $this->config->save();
    } catch (\JsonException $e) {
    }
  }
}