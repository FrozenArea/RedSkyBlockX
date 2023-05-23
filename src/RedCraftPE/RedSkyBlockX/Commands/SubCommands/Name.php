<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use RedCraftPE\RedSkyBlockX\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlockX\Island;

class Name extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		$island = $this->plugin->islandManager->getIslandAtPlayer($sender);
		if ($island instanceof Island) {

			$islandName = $island->getName();

			$message = $this->getMShop()->construct("ISLAND_NAME");
			$message = str_replace("{ISLAND_NAME}", $islandName, $message);
			$sender->sendTip($message);
		} elseif ($this->checkIsland($sender)) {

			$island = $this->plugin->islandManager->getIsland($sender);
			$islandName = $island->getName();

			$message = $this->getMShop()->construct("ISLAND_NAME");
			$message = str_replace("{ISLAND_NAME}", $islandName, $message);
			$sender->sendTip($message);
		} else {

			$message = $this->getMShop()->construct("NO_ISLAND");
			$sender->sendMessage($message);
		}
	}
}
