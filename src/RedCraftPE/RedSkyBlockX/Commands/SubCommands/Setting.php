<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use RedCraftPE\RedSkyBlockX\Commands\SBSubCommand;

class Setting extends SBSubCommand {

	public function prepare(): void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
		$this->registerArgument(0, new RawStringArgument("setting", false));
		$this->registerArgument(1, new BooleanArgument("value", false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

		if ($this->checkIsland($sender)) {

			$island = $this->plugin->islandManager->getIsland($sender);
			$defaultSettings = $island->getDefaultSettings();
			$setting = $args["setting"];
			if (array_key_exists($setting, $defaultSettings)) {

				$bias = $args["value"];
				$biasStringVal = "off";

				if ($bias) {

					$biasStringVal = "on";
				} else {

					$biasStringVal = "off";
				}

				$island->changeSetting($setting, $bias);

				$message = $this->getMShop()->construct("SETTING_CHANGED");
				$message = str_replace("{SETTING}", $setting, $message);
				$message = str_replace("{VALUE}", $biasStringVal, $message);
				$sender->sendMessage($message);
			} else {

				$message = $this->getMShop()->construct("SETTING_NOT_EXIST");
				$message = str_replace("{SETTING}", $setting, $message);
				$sender->sendMessage($message);
			}
		} else {

			$message = $this->getMShop()->construct("NO_ISLAND");
			$sender->sendMessage($sender);
		}
	}
}
