<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TextArgument;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use NhanAZ\RedSkyBlockX\Island;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use function intval;
use function str_replace;
use function strval;

class IncreaseSize extends SBSubCommand {

	public function prepare() : void {
		$this->setPermission("redskyblockx.admin");
		$this->registerArgument(0, new IntegerArgument("amount", false));
		$this->registerArgument(1, new TextArgument("name", false));
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
		$playerName = $args["name"];
		$addAmount = intval($args["amount"]);
		$island = $this->plugin->islandManager->getIslandByCreatorName($playerName);
		if ($island instanceof Island) {
			$newSize = $island->getSize() + $addAmount;
			$maxSize = intval($this->plugin->cfg->get("Island Max Size"));
			if ($newSize > $maxSize) $newSize = $maxSize;
			$island->setSize($newSize);
			$message = $this->getMShop()->construct("PLAYER_ISLAND_SIZE_CHANGE");
			$message = str_replace("{NAME}", strval($island->getCreator()), $message);
			$message = str_replace("{SIZE}", strval($newSize), $message);
			$sender->sendMessage($message);
			$player = $this->plugin->getServer()->getPlayerExact($playerName);
			if ($player instanceof Player) {
				$message = $this->getMShop()->construct("ISLAND_SIZE_CHANGED");
				$message = str_replace("{SIZE}", strval($newSize), $message);
				$player->sendMessage($message);
			}
		} else {
			$message = $this->getMShop()->construct("PLAYER_HAS_NO_ISLAND");
			$message = str_replace("{NAME}", $playerName, $message);
			$sender->sendMessage($message);
		}
	}
}
