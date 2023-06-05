<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use NhanAZ\RedSkyBlockX\Island;

class Kick extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
		$this->registerArgument(0, new TextArgument("name", false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player) return;
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
		if ($island === null) return;
		$members = $island->getMembers();
		if (array_key_exists(strtolower($sender->getName()), $members) || $sender->getName() === $island->getCreator() || $sender->hasPermission("redskyblockx.admin")) {

			if (array_key_exists(strtolower($sender->getName()), $members) && !$sender->hasPermission("redskyblockx.admin")) {

				$islandPermissions = $island->getPermissions();
				$senderRank = $members[strtolower($sender->getName())];
				if (in_array("island.kick", $islandPermissions[$senderRank], true)) {

					if (array_key_exists(strtolower($name), $members)) {

						$nameRank = $members[strtolower($name)];
						$memberRanks = Island::MEMBER_RANKS;
						$namePos = array_search($nameRank, $memberRanks, true);
						$senderPos = array_search($senderRank, $memberRanks, true);
						if ($namePos >= $senderPos) {

							$message = $this->getMShop()->construct("CANT_KICK");
							$sender->sendMessage($message);
							return;
						}
					}

					$player = $this->plugin->getServer()->getPlayerByPrefix($name);
					if ($player instanceof Player) {

						$this->kickPlayer($island, $player, $sender);
					} else {

						$message = $this->getMShop()->construct("TARGET_NOT_FOUND");
						$message = str_replace("{NAME}", $name, $message);
						$sender->sendMessage($message);
					}
				} else {

					$message = $this->getMShop()->construct("RANK_TOO_LOW");
					$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
					$sender->sendMessage($message);
				}
			} else {

				$player = $this->plugin->getServer()->getPlayerByPrefix($name);
				if ($player instanceof Player) {

					$this->kickPlayer($island, $player, $sender);
				} else {

					$message = $this->getMShop()->construct("TARGET_NOT_FOUND");
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

	public function kickPlayer(Island $island, Player $player, CommandSender $sender): void {

		if ($this->plugin->islandManager->isOnIsland($player, $island)) {

			if ($island->getCreator() !== $player->getName()) {

				$message = $this->getMShop()->construct("KICKED_PLAYER");
				$message = str_replace("{NAME}", $player->getName(), $message);
				$sender->sendMessage($message);

				$message = $this->getMShop()->construct("KICKED_FROM_ISLAND");
				$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
				$player->sendMessage($message);

				$spawn = $this->plugin->getServer()->getWorldManager()->getDefaultWorld();
				if ($spawn === null) return;
				$spawn = $spawn->getSafeSpawn();
				$player->teleport($spawn);
			} else {

				$message = $this->getMShop()->construct("CANT_KICK");
				$sender->sendMessage($message);
			}
		} else {

			$message = $this->getMShop()->construct("TARGET_NOT_FOUND");
			$message = str_replace("{NAME}", $player->getName(), $message);
			$sender->sendMessage($message);
		}
	}
}
