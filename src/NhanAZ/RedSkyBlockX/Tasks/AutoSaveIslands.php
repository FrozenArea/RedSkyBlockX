<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Tasks;

use NhanAZ\RedSkyBlockX\SkyBlock;
use pocketmine\scheduler\Task;

class AutoSaveIslands extends Task {

	private SkyBlock $plugin;

	public function __construct(SkyBlock $plugin) {

		$this->plugin = $plugin;
	}

	public function onRun() : void {

		$this->plugin->islandManager->saveAllIslands();
	}
}
