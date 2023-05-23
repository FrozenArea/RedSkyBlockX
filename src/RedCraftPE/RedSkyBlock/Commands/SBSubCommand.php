<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlock\Commands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Utils\MessageConstructor;
use RedCraftPE\RedSkyBlock\Utils\ZoneManager;

abstract class SBSubCommand extends BaseSubCommand {

	protected $plugin;

	public function __construct(SkyBlock $plugin, string $name, string $description = "", array $aliases = []) {

		$this->plugin = $plugin;
		parent::__construct($name, $description, $aliases);
	}

	//include get SB functions here + any other useful functions to be used across multiple commands

	public function getMShop(): MessageConstructor {

		return MessageConstructor::getInstance();
	}

	public function checkZone(): bool {

		if (ZoneManager::getZone() !== []) {

			return true;
		} else {

			return false;
		}
	}

	public function checkMasterWorld(): bool {

		if ($this->plugin->skyblock->get("Master World") !== false) {

			return true;
		} else {

			return false;
		}
	}

	public function checkIsland(Player $player): bool {

		$playerFiles = scandir($this->plugin->getDataFolder() . "../RedSkyBlock/Players");
		$playerName = $player->getName();

		if (in_array($playerName . ".json", $playerFiles, true)) {

			return true;
		} else {

			return false;
		}
	}
}
