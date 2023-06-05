<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use NhanAZ\RedSkyBlockX\Island;
use pocketmine\player\Player;

class Name extends SBSubCommand {

	public function prepare(): void {
		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player) return;
		$island = $this->plugin->islandManager->getIslandAtPlayer($sender);
		if ($island instanceof Island) {
			$islandName = $island->getName();
			$message = $this->getMShop()->construct("ISLAND_NAME");
			$message = str_replace("{ISLAND_NAME}", $islandName, $message);
			$sender->sendMessage($message);
		} elseif ($this->checkIsland($sender)) {
			$island = $this->plugin->islandManager->getIsland($sender);
			if ($island === null) return;
			$islandName = $island->getName();
			$message = $this->getMShop()->construct("ISLAND_NAME");
			$message = str_replace("{ISLAND_NAME}", $islandName, $message);
			$sender->sendMessage($message);
		} else {
			$message = $this->getMShop()->construct("NO_ISLAND");
			$sender->sendMessage($message);
		}
	}
}
