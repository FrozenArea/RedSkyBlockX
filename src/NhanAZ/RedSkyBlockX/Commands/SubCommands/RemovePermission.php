<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;

class RemovePermission extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
		$this->registerArgument(0, new RawStringArgument("rank", false));
		$this->registerArgument(1, new RawStringArgument("permission", false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		$rank = strtolower($args["rank"]);
		$permission = strtolower($args["permission"]);

		if ($this->checkIsland($sender)) {

			$island = $this->plugin->islandManager->getIsland($sender);
			if ($island->removePermission($rank, $permission)) {

				$message = $this->getMShop()->construct("PERMISSION_REMOVED");
				$message = str_replace("{PERMISSION}", $permission, $message);
				$message = str_replace("{RANK}", ucfirst($rank), $message);
				$sender->sendMessage($message);
			} else {

				$message = $this->getMShop()->construct("PERMISSION_NOT_REMOVED");
				$message = str_replace("{PERMISSION}", $permission, $message);
				$message = str_replace("{RANK}", $rank, $message);
				$sender->sendMessage($message);
			}
		} else {

			$message = $this->getMShop()->construct("NO_ISLAND");
			$sender->sendMessage($message);
		}
	}
}
