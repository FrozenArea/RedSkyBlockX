<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use RedCraftPE\RedSkyBlockX\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlockX\Island;

class Level extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player) return;
		$island = $this->plugin->islandManager->getIslandAtPlayer($sender);
		if ($island instanceof Island) {
			$islandLevel = $island->calculateLevel($island->getXP());
			$xpNeeded = $island->getXPNeeded($island->getXP()) + $island->getXP();

			$message = $this->getMShop()->construct("ISLAND_LEVEL_OTHER");
			$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
			$message = str_replace("{LEVEL}", (string) $islandLevel, $message);
			$message = str_replace("{XP}", (string) $island->getXP(), $message);
			$message = str_replace("{XP_NEEDED}", (string) $xpNeeded, $message);
			$sender->sendMessage($message);
		} elseif ($this->checkIsland($sender)) {

			$island = $this->plugin->islandManager->getIsland($sender);
			$islandLevel = $island->calculateLevel($island->getXP());
			$xpNeeded = $island->getXPNeeded($island->getXP()) + $island->getXP();

			$message = $this->getMShop()->construct("ISLAND_LEVEL_SELF");
			$message = str_replace("{LEVEL}", $islandLevel, $message);
			$message = str_replace("{XP}", $island->getXP(), $message);
			$message = str_replace("{XP_NEEDED}", $xpNeeded, $message);
			$sender->sendMessage($message);
		} else {

			$message = $this->getMShop()->construct("NO_ISLAND");
			$sender->sendMessage($message);
		}
	}
}
