<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands;

use CortexPE\Commando\BaseSubCommand;
use NhanAZ\RedSkyBlockX\SkyBlock;
use NhanAZ\RedSkyBlockX\Utils\MessageConstructor;
use NhanAZ\RedSkyBlockX\Utils\ZoneManager;
use pocketmine\player\Player;
use function in_array;
use function scandir;

abstract class SBSubCommand extends BaseSubCommand {

	protected SkyBlock $plugin;

	/**
	 * @param array<string> $aliases
	 */
	public function __construct(SkyBlock $plugin, string $name, string $description = "", array $aliases = []) {

		$this->plugin = $plugin;
		parent::__construct($name, $description, $aliases);
	}

	//include get SB functions here + any other useful functions to be used across multiple commands

	public function getMShop() : MessageConstructor {

		return MessageConstructor::getInstance();
	}

	public function checkZone() : bool {

		if (ZoneManager::getZone() !== []) {

			return true;
		} else {

			return false;
		}
	}

	public function checkMasterWorld() : bool {

		if ($this->plugin->skyblock->get("Master World") !== false) {

			return true;
		} else {

			return false;
		}
	}

	public function checkIsland(Player $player) : bool {

		$playerFiles = scandir($this->plugin->getDataFolder() . "../RedSkyBlockX/Players");
		$playerName = $player->getName();

		if (in_array($playerName . ".json", $playerFiles, true)) {

			return true;
		} else {

			return false;
		}
	}
}
