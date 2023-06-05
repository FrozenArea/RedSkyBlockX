<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Utils;

use NhanAZ\RedSkyBlockX\SkyBlock;
use function array_diff;
use function array_keys;
use function count;
use function file_get_contents;
use function file_put_contents;
use function stream_get_contents;
use function yaml_emit;
use function yaml_parse;

class ConfigManager {

	private SkyBlock $plugin;

	public function __construct(SkyBlock $plugin) {
		$this->plugin = $plugin;
		$this->verifyConfig();
	}

	public function verifyConfig() : void {
		$real = yaml_parse(file_get_contents($this->plugin->getDataFolder() . "../RedSkyBlockX/config.yml"));
		$realKeys = array_keys($real);
		$reference = yaml_parse(stream_get_contents($this->plugin->getResource("config.yml")));
		$referenceKeys = array_keys($reference);
		$compare = array_diff($referenceKeys, $realKeys);
		if (count($compare) > 0) {
			foreach ($compare as $key) {
				$real[$key] = $reference[$key];
			}
			$updated = yaml_emit($real);
			file_put_contents($this->plugin->getDataFolder() . "../RedSkyBlockX/config.yml", $updated);
			$this->plugin->cfg->reload();
		}
	}
}
