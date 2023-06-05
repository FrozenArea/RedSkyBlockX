<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use NhanAZ\RedSkyBlockX\Island;

class Demote extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
		$this->registerArgument(0, new TextArgument("player", false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player) return;
		if ($this->checkIsland($sender)) {

			$playerName = strtolower($args["player"]);
			$island = $this->plugin->islandManager->getIsland($sender);
			if ($island === null) return;
			$members = $island->getMembers();
			if (array_key_exists($playerName, $members)) {

				$currentRank = $members[$playerName];
				$possibleRanks = Island::MEMBER_RANKS;
				$lowestRank = $possibleRanks[0];
				if ($currentRank !== $lowestRank) {

					$index = array_search($currentRank, $possibleRanks, true);
					$newRank = $possibleRanks[$index - 1];
					$island->setRank($playerName, $newRank);

					$message = $this->getMShop()->construct("DEMOTED_OTHER");
					$message = str_replace("{RANK}", ucfirst($newRank), $message);
					$message = str_replace("{NAME}", $playerName, $message);
					$sender->sendMessage($message);

					$player = $this->plugin->getServer()->getPlayerExact($playerName);
					if ($player instanceof Player) {

						$message = $this->getMShop()->construct("DEMOTED_SELF");
						$message = str_replace("{RANK}", ucfirst($newRank), $message);
						$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
						$player->sendMessage($message);
					}
				} else {

					$message = $this->getMShop()->construct("CANT_DEMOTE");
					$message = str_replace("{NAME}", $args["player"], $message);
					$sender->sendMessage($message);
				}
			} else {

				$message = $this->getMShop()->construct("NOT_A_MEMBER_OTHER");
				$message = str_replace("{NAME}", $args["player"], $message);
				$sender->sendMessage($message);
			}
		} else {

			$message = $this->getMShop()->construct("NO_ISLAND");
			$sender->sendMessage($message);
		}
	}
}
