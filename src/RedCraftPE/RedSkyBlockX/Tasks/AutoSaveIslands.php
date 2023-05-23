<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Tasks;

use pocketmine\scheduler\Task;
use RedCraftPE\RedSkyBlockX\SkyBlock;

class AutoSaveIslands extends Task {

	private $plugin;

	public function __construct(SkyBlock $plugin) {

		$this->plugin = $plugin;
	}

	public function onRun(): void {

		$this->plugin->islandManager->saveAllIslands();
	}
}
