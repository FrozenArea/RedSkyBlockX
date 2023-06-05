<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use muqsit\invmenu\InvMenu;
use pocketmine\block\utils\MobHeadType;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use pocketmine\player\Player;

class Members extends SBSubCommand {

	public function prepare(): void {
		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player) return;
		if ($this->checkIsland($sender)) {
			$island = $this->plugin->islandManager->getIsland($sender);
			if ($island === null) return;
			$members = $island->getMembers();
			$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
			$menu->setName(TextFormat::RED . TextFormat::BOLD . $island->getName() . TextFormat::RESET . " Members");
			foreach ($members as $member => $rank) {
				$item = VanillaBlocks::MOB_HEAD()->setMobHeadType(MobHeadType::PLAYER())->asItem();
				$item->setCustomName($member);
				$item->setLore([$rank]);
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
