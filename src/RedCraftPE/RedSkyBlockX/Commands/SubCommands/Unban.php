<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use RedCraftPE\RedSkyBlockX\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlockX\Island;

class Unban extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
		$this->registerArgument(0, new TextArgument("name", false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		$name = $args["name"];
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

		$creator = $island->getCreator();
		$members = $island->getMembers();

		if (array_key_exists(strtolower($sender->getName()), $members) || $sender->getName() === $island->getCreator() || $sender->hasPermission("redskyblockx.admin")) {

			if (array_key_exists(strtolower($sender->getName()), $members) && !$sender->hasPermission("redskyblockx.admin")) {

				$islandPermissions = $island->getPermissions();
				$senderRank = $members[strtolower($sender->getName())];

				if (in_array("island.ban", $islandPermissions[$senderRank], true)) {

					if ($island->unban($name)) {

						$message = $this->getMShop()->construct("UNBANNED");
						$message = str_replace("{NAME}", $name, $message);
						$sender->sendMessage($message);

						$player = $this->plugin->getServer()->getPlayerExact($name);
						if ($player instanceof Player) {

							$message = $this->getMShop()->construct("NO_LONGER_BANNED");
							$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
							$player->sendMessage($message);
						}
					} else {

						$message = $this->getMShop()->construct("NOT_BANNED");
						$message = str_replace("{NAME}", $name, $message);
						$sender->sendMessage($message);
					}
				} else {

					$message = $this->getMShop()->construct("RANK_TOO_LOW");
					$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
					$sender->sendMessage($message);
				}
			} else {

				if ($island->unban($name)) {

					$message = $this->getMShop()->construct("UNBANNED");
					$message = str_replace("{NAME}", $name, $message);
					$sender->sendMessage($message);

					$player = $this->plugin->getServer()->getPlayerExact($name);
					if ($player instanceof Player) {

						$message = $this->getMShop()->construct("NO_LONGER_BANNED");
						$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
						$player->sendMessage($message);
					}
				} else {

					$message = $this->getMShop()->construct("NOT_BANNED");
					$message = str_replace("{NAME}", $name, $message);
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
