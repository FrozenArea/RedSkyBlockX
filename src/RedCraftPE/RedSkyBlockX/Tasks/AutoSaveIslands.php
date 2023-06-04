<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Tasks;

use pocketmine\scheduler\Task;
use RedCraftPE\RedSkyBlockX\SkyBlock;

class AutoSaveIslands extends Task {

	public function __construct(private SkyBlock $plugin) {
	}

	public function onRun(): void {

		$this->plugin->islandManager->saveAllIslands();
	}
}
