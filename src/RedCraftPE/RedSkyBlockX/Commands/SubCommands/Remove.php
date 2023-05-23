<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use RedCraftPE\RedSkyBlockX\Commands\SBSubCommand;

class Remove extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
		$this->registerArgument(0, new TextArgument("name", false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		if (isset($args["name"])) {

			if ($this->checkIsland($sender)) {

				$name = $args["name"];
				$island = $this->plugin->islandManager->getIsland($sender);
				$creator = $island->getCreator();

				if (strtolower($name) !== strtolower($creator)) {

					if ($island->removeMember($name)) {

						$message = $this->getMShop()->construct("MEMBER_REMOVED");
						$message = str_replace("{NAME}", $name, $message);
						$sender->sendMessage($message);

						$player = $this->plugin->getServer()->getPlayerExact($name);
						if ($player instanceof Player) {

							$message = $this->getMShop()->construct("REMOVED_FROM_ISLAND");
							$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
							$player->sendMessage($message);
						}
					} else {

						$message = $this->getMShop()->construct("NOT_A_MEMBER_OTHER");
						$message = str_replace("{NAME}", $name, $message);
						$sender->sendMessage($message);
					}
				} else {

					$message = $this->getMShop()->construct("CANT_REMOVE_SELF");
					$sender->sendMessage($message);
				}
			} else {

				$message = $this->getMShop()->construct("NO_ISLAND");
				$sender->sendMessage($message);
			}
		} else {

			$this->sendUsage();
			return;
		}
	}
}
