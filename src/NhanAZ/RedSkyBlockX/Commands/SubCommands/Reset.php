<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use pocketmine\player\Player;

class Reset extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player) return;
		if ($this->checkIsland($sender)) {

			$island = $this->plugin->islandManager->getIsland($sender);
			if ($island === null) return;
			$resetCooldown = $island->getResetCooldown();

			if (Time() >= $resetCooldown) {

				$playersOnIsland = $this->plugin->islandManager->getPlayersAtIsland($island);
				$this->plugin->islandManager->deleteIsland($island);
				Create::getInstance()->onRun($sender, "create", $args);

				foreach ($playersOnIsland as $playerName) {

					$player = $this->plugin->getServer()->getPlayerExact($playerName);
					if ($player === null) return;
					$message = $this->getMShop()->construct("ISLAND_ON_DELETED");
					$player->sendMessage($message);
					$spawn = $this->plugin->getServer()->getWorldManager()->getDefaultWorld();
					if ($spawn === null) return;
					$spawn = $spawn->getSafeSpawn();
					$player->teleport($spawn);
				}
			} else {

				$timeLeft = gmdate("H:i:s", $resetCooldown - Time());
				$message = $this->getMShop()->construct("CANT_RESET_YET");
				$message = str_replace("{TIME}", $timeLeft, $message);
				$sender->sendMessage($message);
			}
		} else {

			$message = $this->getMShop()->construct("NO_ISLAND");
			$sender->sendMessage($message);
		}
	}
}
