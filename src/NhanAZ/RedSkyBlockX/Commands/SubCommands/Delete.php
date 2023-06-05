<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\TextArgument;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use NhanAZ\RedSkyBlockX\Island;

class Delete extends SBSubCommand {

	public function prepare(): void {

		$this->setPermission("redskyblockx.admin");
		$this->registerArgument(0, new TextArgument("island", false));
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		$islandName = $args["island"];
		$island = $this->plugin->islandManager->getIslandByName($islandName);
		if ($island instanceof Island) {

			$playersOnIsland = $this->plugin->islandManager->getPlayersAtIsland($island);
			$spawn = $this->plugin->getServer()->getWorldManager()->getDefaultWorld();
			if ($spawn === null) return;
			$spawn = $spawn->getSafeSpawn();

			$islandCreator = $this->plugin->getServer()->getPlayerExact($island->getCreator());
			if ($islandCreator instanceof Player) {

				$isOnIsland = $this->plugin->islandManager->isOnIsland($islandCreator, $island);
				if ($isOnIsland) $islandCreator->teleport($spawn);
				$message = $this->getMShop()->construct("ISLAND_DELETED");
				$islandCreator->sendMessage($message);
			}
			$this->plugin->islandManager->deleteIsland($island);

			foreach ($playersOnIsland as $playerName) {

				$player = $this->plugin->getServer()->getPlayerExact($playerName);
				if ($player === null) return;
				$message = $this->getMShop()->construct("ISLAND_ON_DELETED");
				$player->sendMessage($message);
				$player->teleport($spawn);
			}
		} else {

			$message = $this->getMShop()->construct("COULD_NOT_FIND_ISLAND");
			$message = str_replace("{ISLAND_NAME}", $islandName, $message);
			$sender->sendMessage($message);
		}
	}
}
