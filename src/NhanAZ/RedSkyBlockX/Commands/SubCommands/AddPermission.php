<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use function str_replace;
use function strtolower;
use function ucfirst;

class AddPermission extends SBSubCommand {

	public function prepare() : void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
		$this->registerArgument(0, new RawStringArgument("rank", false));
		$this->registerArgument(1, new RawStringArgument("permission", false));
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		if (!$sender instanceof Player) return;
		$rank = strtolower($args["rank"]);
		$permission = strtolower($args["permission"]);

		if ($this->checkIsland($sender)) {

			$island = $this->plugin->islandManager->getIsland($sender);
			if ($island === null) return;
			if ($island->addPermission($rank, $permission)) {

				$message = $this->getMShop()->construct("PERMISSION_ADDED");
				$message = str_replace("{PERMISSION}", $permission, $message);
				$message = str_replace("{RANK}", ucfirst($rank), $message);
				$sender->sendMessage($message);
			} else {

				$message = $this->getMShop()->construct("PERMISSION_NOT_ADDED");
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
