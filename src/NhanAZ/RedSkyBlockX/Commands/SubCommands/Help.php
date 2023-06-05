<?php

declare(strict_types=1);

namespace NhanAZ\RedSkyBlockX\Commands\SubCommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use NhanAZ\RedSkyBlockX\Commands\SBSubCommand;
use pocketmine\command\CommandSender;
use function array_slice;
use function array_unique;
use function ceil;
use function count;
use function explode;
use function implode;
use function in_array;
use function intval;
use function str_replace;
use function strtolower;

class Help extends SBSubCommand {

	public function prepare() : void {

		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->setPermission("redskyblockx.island");
		$this->registerArgument(0, new IntegerArgument("page#", true));
		$this->registerArgument(0, new RawStringArgument("command", true));
	}

	/**
	 * @param array<string> $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {

		$islandCommand = $this->parent;
		$subCommands = $islandCommand->getSubCommands();
		$subCommandNames = [];
		foreach ($subCommands as $command) {

			$subCommandNames[] = $command->getName();
		}
		$subCommandNames = array_unique($subCommandNames);
		$pageCount = (int) ceil(count($subCommandNames) / 6);

		if (isset($args["command"])) {

			$commandName = strtolower($args["command"]);
			if (in_array($commandName, $subCommandNames, true)) {

				$command = $subCommands[$commandName];
				$commandDescription = $command->getDescription();
				$commandUsage = $command->getUsageMessage();
				$commandPermissions = $command->getPermission();
				if ($commandPermissions === null) return;
				$commandPermissions = implode(" or ", explode(";", $commandPermissions));
				$commandAliases = $command->getAliases();
				if (count($commandAliases) !== 0) {

					$commandAliases = implode(", ", $commandAliases);
				} else {

					$commandAliases = "N/A";
				}

				$message = $this->getMShop()->construct("HELP_MENU_SPECIFIC");
				$message = str_replace("{COMMAND}", $commandName, $message);
				$message = str_replace("{DESCRIPTION}", $commandDescription, $message);
				$message = str_replace("{USAGE}", $commandUsage, $message);
				$message = str_replace("{PERMISSIONS}", $commandPermissions, $message);
				$message = str_replace("{ALIASES}", $commandAliases, $message);
				$sender->sendMessage($message);
			} else {

				$message = $this->getMShop()->construct("NO_SUCH_COMMAND");
				$message = str_replace("{COMMAND}", $commandName, $message);
				$sender->sendMessage($message);
			}
		} else {

			if (isset($args["page#"])) {

				$pageNumber = $args["page#"];
				if ($pageNumber > $pageCount) $pageNumber = $pageCount;
				if ($pageNumber <= 0) $pageNumber = 1;
			} else {
				$pageNumber = 1;
			}

			$pageNumber = intval($pageNumber) - 1;
			$index = $pageNumber * 6;
			$commandsOnPage = array_slice($subCommandNames, $index, 6);
			$command1 = "";
			$command2 = "";
			$command3 = "";
			$command4 = "";
			$command5 = "";
			$command6 = "";

			for ($x = 0; $x < count($commandsOnPage); $x++) {

				${"command" . $x + 1} = "/is " . $commandsOnPage[$x];
			}

			$message = $this->getMShop()->construct("HELP_MENU");
			$message = str_replace("{PAGE_NUMBER}", (string) ($pageNumber + 1), $message);
			$message = str_replace("{TOTAL_PAGES}", (string) $pageCount, $message);
			$message = str_replace("{COMMAND_ONE}", $command1, $message);
			$message = str_replace("{COMMAND_TWO}", $command2, $message);
			$message = str_replace("{COMMAND_THREE}", $command3, $message);
			$message = str_replace("{COMMAND_FOUR}", $command4, $message);
			$message = str_replace("{COMMAND_FIVE}", $command5, $message);
			$message = str_replace("{COMMAND_SIX}", $command6, $message);
			$sender->sendMessage($message);
		}
	}
}
