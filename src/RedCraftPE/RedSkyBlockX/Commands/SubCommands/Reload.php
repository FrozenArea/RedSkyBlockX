<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Commands\SubCommands;

use pocketmine\command\CommandSender;
use RedCraftPE\RedSkyBlockX\Commands\SBSubCommand;

class Reload extends SBSubCommand {

	public function prepare(): void {
		$this->setPermission("redskyblockx.admin;redskyblockx.reload");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		$plugin = $this->plugin;
		$plugin->cfg->reload();
		$plugin->skyblock->reload();
		$plugin->messages->reload();

		$message = $this->getMShop()->construct("RELOAD");
		$sender->sendMessage($message);
		return;
	}
}
