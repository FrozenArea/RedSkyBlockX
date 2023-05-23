<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use muqsit\invmenu\InvMenu;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat;
use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

class Banned extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblock.island");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		if ($this->checkIsland($sender)) {

			$island = $this->plugin->islandManager->getIsland($sender);
			$banned = $island->getBanned();
			$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
			$menu->setName(TextFormat::RED . TextFormat::BOLD . $island->getName() . TextFormat::RESET . " Banned Players");

			foreach ($banned as $player) {

				$item = VanillaItems::PLAYER_HEAD();
				$item->setCustomName($player);
				$item->setLore(["Banned From " . $island->getName()]);
				$menu->getInventory()->addItem($item);
			}

			$menu->setListener(InvMenu::readonly());
			$menu->send($sender);
		} else {

			$message = $this->getMShop()->construct("NO_ISLAND");
			$sender->sendMessage($message);
		}
	}
}
