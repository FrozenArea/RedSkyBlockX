<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use RedCraftPE\RedSkyBlockX\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlockX\Island;

class Rank extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
		$this->registerArgument(0, new TextArgument("island", true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		$islandCount = count($this->plugin->islandManager->getIslands());

		if (isset($args["island"])) {

			$islandName = $args["island"];
			$island = $this->plugin->islandManager->getIslandByName($islandName);
			if ($island instanceof Island) {

				$rank = $this->plugin->islandManager->getIslandRank($island);

				$message = $this->getMShop()->construct("ISLAND_RANK_OTHER");
				$message = str_replace("{NAME}", $islandName, $message);
				$message = str_replace("{RANK}", $rank, $message);
				$message = str_replace("{TOTAL_ISLANDS}", (string) $islandCount, $message);
				$sender->sendMessage($message);
			} else {

				$message = $this->getMShop()->construct("COULD_NOT_FIND_ISLAND");
				$message = str_replace("{ISLAND_NAME}", $islandName, $message);
				$sender->sendMessage($message);
			}
		} else {

			if ($this->checkIsland($sender)) {

				$island = $this->plugin->islandManager->getIsland($sender);
				$rank = $this->plugin->islandManager->getIslandrank($island);

				$message = $this->getMShop()->construct("ISLAND_RANK_SELF");
				$message = str_replace("{RANK}", $rank, $message);
				$message = str_replace("{TOTAL_ISLANDS}", (string) $islandCount, $message);
				$sender->sendMessage($message);
			} else {

				$message = $this->getMShop()->construct("NO_ISLAND");
				$sender->sendMessage($message);
			}
		}
	}
}
