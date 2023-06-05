<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use NhanAZ\RedSkyBlockX\Island;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use function array_key_exists;
use function in_array;
use function str_replace;
use function strtolower;

class Chat extends SBSubCommand {

	public function prepare() : void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
		$this->registerArgument(0, new TextArgument("island", true));
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (!$sender instanceof Player) return;
		if (isset($args["island"])) {

			$islandName = $args["island"];
			$island = $this->plugin->islandManager->getIslandByName($islandName);
			if ($island instanceof Island) {

				if (array_key_exists(strtolower($sender->getName()), $island->getMembers()) || $sender->getName() === $island->getCreator() || $sender->hasPermission("redskyblockx.admin")) {

					$islandChatters = $island->getChatters();
					if (in_array(strtolower($sender->getName()), $islandChatters, true)) {

						//if already in island chat be removed from it
						$island->removeChatter($sender->getName());

						$message = $this->getMShop()->construct("LEAVE_ISLAND_CHAT");
						$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
						$sender->sendMessage($message);
					} else {

						//if not already in chat then check if in any other island chats. if yes be removed from them.
						$currentChannel = $this->plugin->islandManager->searchIslandChannels($sender->getName());
						if ($currentChannel instanceof Island) {

							$currentChannel->removeChatter($sender->getName());

							$message = $this->getMShop()->construct("LEAVE_ISLAND_CHAT");
							$message = str_replace("{ISLAND_NAME}", $currentChannel->getName(), $message);
							$sender->sendMessage($message);
						}

						$island->addChatter($sender->getName());

						$message = $this->getMShop()->construct("JOIN_ISLAND_CHAT");
						$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
						$sender->sendMessage($message);
					}
				} else {

					$message = $this->getMShop()->construct("NOT_A_MEMBER_SELF");
					$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
					$sender->sendMessage($message);
				}
			} else {

				$message = $this->getMShop()->construct("COULD_NOT_FIND_ISLAND");
				$message = str_replace("{ISLAND_NAME}", $islandName, $message);
				$sender->sendMessage($message);
			}
		} else {

			$currentChannel = $this->plugin->islandManager->searchIslandChannels($sender->getName());

			if ($currentChannel instanceof Island) {

				$currentChannel->removeChatter($sender->getName());

				$message = $this->getMShop()->construct("LEAVE_ISLAND_CHAT");
				$message = str_replace("{ISLAND_NAME}", $currentChannel->getName(), $message);
				$sender->sendMessage($message);
			} else {

				if ($this->checkIsland($sender)) {

					$island = $this->plugin->islandManager->getIsland($sender);
					if ($island === null) return;
					$island->addChatter($sender->getName());

					$message = $this->getMShop()->construct("JOIN_ISLAND_CHAT");
					$message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
					$sender->sendMessage($message);
				} else {

					$message = $this->getMShop()->construct("NO_ISLAND");
					$sender->sendMessage($message);
				}
			}
		}
	}
}
