<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use NhanAZ\RedSkyBlockX\Island;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use function array_key_exists;
use function in_array;
use function str_replace;
use function strtolower;

class Lock extends SBSubCommand {

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
		if (!($island instanceof Island)) {

			if ($this->checkIsland($sender)) {

				$island = $this->plugin->islandManager->getIsland($sender);
			} else {

				$message = $this->getMShop()->construct("NO_ISLAND");
				$sender->sendMessage($message);
				return;
			}
		}
		if ($island === null) return;
		$members = $island->getMembers();
		if (array_key_exists(strtolower($sender->getName()), $members) || $sender->getName() === $island->getCreator() || $sender->hasPermission("redskyblockx.admin")) {

			if (array_key_exists(strtolower($sender->getName()), $members) && !$sender->hasPermission("redskyblockx.admin")) {

				$islandPermissions = $island->getPermissions();
				$senderRank = $members[strtolower($sender->getName())];

				if (in_array("island.lock", $islandPermissions[$senderRank], true)) {

					if ($island->lock()) {

						$message = $this->getMShop()->construct("LOCKED");
						$sender->sendMessage($message);
					} else {

						$message = $this->getMShop()->construct("ALREADY_LOCKED");
						$sender->sendMessage($message);
					}
				} else {

					$message = $this->getMShop()->construct("RANK_TOO_LOW");
					$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
					$sender->sendMessage($message);
				}
			} else {

				if ($island->lock()) {

					$message = $this->getMShop()->construct("LOCKED");
					$sender->sendMessage($message);
				} else {

					$message = $this->getMShop()->construct("ALREADY_LOCKED");
					$sender->sendMessage($message);
				}
			}
		} else {

			$message = $this->getMShop()->construct("NOT_A_MEMBER_SELF");
			$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
			$sender->sendMessage($message);
		}
	}
}
