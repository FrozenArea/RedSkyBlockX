<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use NhanAZ\RedSkyBlockX\Utils\ZoneManager;
use pocketmine\item\Item;

class ZoneTools extends SBSubCommand {

	private Item $zoneShovel;
	private Item $spawnFeather;

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.admin;redskyblockx.zone");
		$this->zoneShovel = ZoneManager::getZoneShovel();
		$this->spawnFeather = ZoneManager::getSpawnFeather();
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		if (!$sender instanceof Player) return;
		$zoneKeeper = ZoneManager::getZoneKeeper();
		$senderInv = $sender->getInventory();
		$senderContents = $senderInv->getContents();
		$zoneShovel = clone $this->zoneShovel;
		$spawnFeather = clone $this->spawnFeather;

		if ($zoneKeeper != $sender) {

			if ($zoneKeeper == null) {

				ZoneManager::clearZoneTools($sender);
				$senderInv->addItem($zoneShovel);
				$senderInv->addItem($spawnFeather);
				ZoneManager::setZoneKeeper($sender);
				ZoneManager::setSpawnPosition();
				ZoneManager::setFirstPosition();
				ZoneManager::setSecondPosition();
				return;
			} else {

				ZoneManager::clearZoneTools($zoneKeeper);
				ZoneManager::setZoneKeeper($sender);
				ZoneManager::setSpawnPosition();
				ZoneManager::setFirstPosition();
				ZoneManager::setSecondPosition();
				$senderInv->addItem($zoneShovel);
				$senderInv->addItem($spawnFeather);
				return;
			}
		} elseif (!$senderInv->contains($zoneShovel) || !$senderInv->contains($spawnFeather)) {

			ZoneManager::clearZoneTools($sender);
			$senderInv->addItem($zoneShovel);
			$senderInv->addItem($spawnFeather);
			return;
		}

		return;
	}
}
