<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use RedCraftPE\RedSkyBlockX\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlockX\Utils\ZoneManager;

class UpdateZone extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.admin;redskyblockx.zone");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		if ($this->checkZone()) {

			ZoneManager::updateZone();

			$message = $this->getMShop()->construct("UPDATE_ZONE");
			$sender->sendMessage($message);
		} else {

			$message = $this->getMShop()->construct("NO_ZONE");
			$sender->sendMessage($message);
		}
	}
}
