<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\RawStringArgument;
use pocketmine\command\CommandSender;
use pocketmine\world\generator\GeneratorManager;
use pocketmine\world\WorldCreationOptions;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;

class CreateWorld extends SBSubCommand {

  protected function prepare() : void {

	$this->setPermission("redskyblockx.admin;redskyblockx.createworld");
	$this->registerArgument(0, new RawStringArgument("name", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {

	if (isset($args["name"])) {

	  $name = $args["name"];
	  $plugin = $this->plugin;

	  if (!$plugin->getServer()->getWorldManager()->loadWorld($name)) {
		$generator = GeneratorManager::getInstance()->getGenerator("flat");
		if ($generator === null) return;
		$generator = $generator->getGeneratorClass();
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
