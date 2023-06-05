<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use NhanAZ\RedSkyBlockX\Island;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use function str_replace;
use function strval;

class Level extends SBSubCommand {

	public function prepare() : void {
		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
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
			if ($island === null) return;
			$islandLevel = $island->calculateLevel($island->getXP());
			$xpNeeded = $island->getXPNeeded($island->getXP()) + $island->getXP();
			$message = $this->getMShop()->construct("ISLAND_LEVEL_SELF");
			$message = str_replace("{LEVEL}", strval($islandLevel), $message);
			$message = str_replace("{XP}", strval($island->getXP()), $message);
			$message = str_replace("{XP_NEEDED}", strval($xpNeeded), $message);
			$sender->sendMessage($message);
		} else {
			$message = $this->getMShop()->construct("NO_ISLAND");
			$sender->sendMessage($message);
		}
	}
}
