<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use CortexPE\Commando\args\RawStringArgument;
use pocketmine\command\CommandSender;
use pocketmine\world\generator\GeneratorManager;
use pocketmine\world\WorldCreationOptions;
use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

class CreateWorld extends SBSubCommand {

  protected function prepare() : void {

	$this->setPermission("redskyblock.admin;redskyblock.createworld");
	$this->registerArgument(0, new RawStringArgument("name", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {

	if (isset($args["name"])) {

	  $name = $args["name"];
	  $plugin = $this->plugin;

	  if (!$plugin->getServer()->getWorldManager()->loadWorld($name)) {

		$generator = GeneratorManager::getInstance()->getGenerator("flat")->getGeneratorClass();
		$worldCreator = WorldCreationOptions::create()->setGeneratorOptions("3;minecraft:air");
		$worldCreator->setGeneratorClass($generator);

		$plugin->getServer()->getWorldManager()->generateWorld($name, $worldCreator);

		$message = $this->getMShop()->construct("CW");
		$message = str_replace("{WORLD}", $name, $message);
		$sender->sendMessage($message);

	  } else {

		$message = $this->getMShop()->construct("CW_EXISTS");
		$message = str_replace("{WORLD}", $name, $message);
		$sender->sendMessage($message);
		return;
	  }
	} else {

	  $this->sendUsage();
	  return;
	}
  }
}
