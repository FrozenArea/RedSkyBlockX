<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Tasks;

use pocketmine\scheduler\Task;
use NhanAZ\RedSkyBlockX\SkyBlock;

class AutoSaveIslands extends Task {

	private SkyBlock $plugin;

	public function __construct(SkyBlock $plugin) {

		$this->plugin = $plugin;
	}

	public function onRun(): void {

		$this->plugin->islandManager->saveAllIslands();
	}
}
