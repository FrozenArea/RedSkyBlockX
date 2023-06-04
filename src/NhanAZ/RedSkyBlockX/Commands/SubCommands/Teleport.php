<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\world\Position;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;

class Teleport extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		if ($this->checkIsland($sender)) {

			$island = $this->plugin->islandManager->getIsland($sender);
			$spawnPoint = $island->getSpawnPoint();
			$masterWorld = $this->plugin->islandManager->getMasterWorld();
			if ($masterWorld === null) return;
			$sender->teleport(new Position($spawnPoint[0], $spawnPoint[1], $spawnPoint[2], $masterWorld));

			$message = $this->getMShop()->construct("GO_HOME");
			$sender->sendMessage($message);
		} else {

			$message = $this->getMShop()->construct("NO_ISLAND");
			$sender->sendMessage($message);
		}
	}
}
