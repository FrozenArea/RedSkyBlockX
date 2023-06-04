<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlockX\Commands;

use CortexPE\Commando\args\RawStringArgument;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Accept;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\AddPermission;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Ban;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Banned;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Chat;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Create;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\CreateWorld;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\DecreaseSize;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Delete;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Demote;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Fly;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Help;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\IncreaseSize;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Info;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Invite;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Kick;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Leave;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Level;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Lock;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Members;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Name;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\OnIsland;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Promote;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Rank;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Reload;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Remove;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\RemovePermission;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Rename;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Reset;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\SetSize;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\SetSpawn;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Setting;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Settings;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\SetWorld;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Teleport;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\TopIslands;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Unban;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Unlock;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\UpdateZone;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Value;
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\Visit;
//todo: get rid of calls to getSafeSpawn (maybe),
//todo (cont'd): garbage collector to remove island objects not being used from memory, nether islands (maybe),
//todo (cont'd): island quests, island leveling system, island permissions for members based on island rank, minions,
//todo (cont'd): skyblock based custom enchants, multiple custom islands, skyblock GUIs, add scoreboard support
//todo (cont'd): add more configurable options in config, obsidian scooping?, island banks (variable economy)
//todo (cont'd): change island identifiers to UUID (maintain backwards compatibility)
use RedCraftPE\RedSkyBlockX\Commands\SubCommands\ZoneTools;
use RedCraftPE\RedSkyBlockX\SkyBlock;

class SBCommand extends BaseCommand {

	public function __construct(private SkyBlock $plugin, string $name, string $description = "", array $aliases = []) {
		parent::__construct($plugin, $name, $description, $aliases);
	}

	protected function prepare(): void {

		$this->registerArgument(0, new RawStringArgument("help", true));

		$this->registerSubCommand(new Accept(
			$this->plugin,
			"accept",
			"Accept an invite to another SkyBlock Island."
		));

		$this->registerSubCommand(new AddPermission(
			$this->plugin,
			"addpermission",
			"Add a permission to an island rank on your SkyBlock Island.",
			["addperm", "setpermission", "setperm"]
		));

		$this->registerSubCommand(new Ban(
			$this->plugin,
			"ban",
			"Ban a player from your SkyBlock Island.",
			["banish"]
		));

		$this->registerSubCommand(new Banned(
			$this->plugin,
			"banned",
			"View the players banned from your SkyBlock island",
			["banished"]
		));

		$this->registerSubCommand(new Chat(
			$this->plugin,
			"chat",
			"Chat with the members of a SkyBlock island."
		));

		$this->registerSubCommand(new Create(
			$this->plugin,
			"create",
			"Create your SkyBlock island!"
		));

		$this->registerSubCommand(new CreateWorld(
			$this->plugin,
			"createworld",
			"Creates a new world ready for SkyBlock!",
			["cw"]
		));

		$this->registerSubCommand(new DecreaseSize(
			$this->plugin,
			"decreasesize",
			"Decrease the size of a player's SkyBlock island.",
			["decrease", "subtractsize", "subtract"]
		));

		$this->registerSubCommand(new Delete(
			$this->plugin,
			"delete",
			"Delete a player's SkyBlock island.",
			["disband", "kill", "eridicate", "expunge", "cancel"]
		));

		$this->registerSubCommand(new Demote(
			$this->plugin,
			"demote",
			"Demote a player on your SkyBlock island."
		));

		$this->registerSubCommand(new Fly(
			$this->plugin,
			"fly",
			"Enable Flight in the SkyBlock world."
		));

		$this->registerSubCommand(new Help(
			$this->plugin,
			"help",
			"Open the RedSkyBlockX Help menu"
		));

		$this->registerSubCommand(new IncreaseSize(
			$this->plugin,
			"increasesize",
			"Increase the size of a player's SkyBlock island.",
			["increase", "addsize"]
		));

		$this->registerSubCommand(new Info(
			$this->plugin,
			"info",
			"See detailed info about the SkyBlock Island you're on."
		));

		$this->registerSubCommand(new Invite(
			$this->plugin,
			"invite",
			"Invite a player to join your SkyBlock island.",
			["coop", "add"]
		));

		$this->registerSubCommand(new Kick(
			$this->plugin,
			"kick",
			"Kick a player off of your SkyBlock island."
		));

		$this->registerSubCommand(new Leave(
			$this->plugin,
			"leave",
			"Resign from a player's SkyBlock island.",
			["quit"]
		));

		$this->registerSubCommand(new Level(
			$this->plugin,
			"level",
			"View a SkyBlock island's level.",
			["xp"]
		));

		$this->registerSubCommand(new Lock(
			$this->plugin,
			"lock",
			"Lock your SkyBlock island.",
			["close"]
		));

		$this->registerSubCommand(new Members(
			$this->plugin,
			"members",
			"View the members of your SkyBlock island."
		));

		$this->registerSubCommand(new Name(
			$this->plugin,
			"name",
			"View the name of the SkyBlock island you are on."
		));

		$this->registerSubCommand(new OnIsland(
			$this->plugin,
			"onisland",
			"View the players on your island.",
			["on"]
		));

		$this->registerSubCommand(new Promote(
			$this->plugin,
			"promote",
			"Promote a player on your SkyBlock island."
		));

		$this->registerSubCommand(new Rank(
			$this->plugin,
			"rank",
			"View the rank of an island"
		));

		$this->registerSubCommand(new Reload(
			$this->plugin,
			"reload",
			"Reloads SkyBlock data files."
		));

		$this->registerSubCommand(new Remove(
			$this->plugin,
			"remove",
			"Remove a member from your SkyBlock island."
		));

		$this->registerSubCommand(new RemovePermission(
			$this->plugin,
			"removepermission",
			"Remove a permission from an island rank on your SkyBlock Island.",
			["removeperm", "unsetpermission", "deletepermission", "deleteperm", "unsetperm"]
		));

		$this->registerSubCommand(new Rename(
			$this->plugin,
			"rename",
			"Renames your SkyBlock island."
		));

		$this->registerSubCommand(new Reset(
			$this->plugin,
			"reset",
			"Reset your SkyBlock island."
		));

		$this->registerSubCommand(new SetSize(
			$this->plugin,
			"setsize",
			"Set the size of a player's island.",
			["size"]
		));

		$this->registerSubCommand(new SetSpawn(
			$this->plugin,
			"setspawn",
			"Changes the spawnpoint on your SkyBlock island."
		));

		$this->registerSubCommand(new Setting(
			$this->plugin,
			"setting",
			"Edit an Island Setting on your SkyBlock island."
		));

		$this->registerSubCommand(new Settings(
			$this->plugin,
			"settings",
			"View and Change settings on your SkyBlock Island."
		));

		$this->registerSubCommand(new SetWorld(
			$this->plugin,
			"setworld",
			"Select a world to use for SkyBlock.",
			["sw"]
		));

		$this->registerSubCommand(new Teleport(
			$this->plugin,
			"teleport",
			"Teleport to your SkyBlock island.",
			["tp", "go", "spawn", "goto"]
		));

		$this->registerSubCommand(new TopIslands(
			$this->plugin,
			"topislands",
			"View the top SkyBlock islands.",
			["top", "leaderboard", "lb"]
		));

		$this->registerSubCommand(new Unban(
			$this->plugin,
			"unban",
			"Unban a player from your SkyBlock island."
		));

		$this->registerSubCommand(new Unlock(
			$this->plugin,
			"unlock",
			"Unlock your SkyBlock island.",
			["open"]
		));

		$this->registerSubCommand(new UpdateZone(
			$this->plugin,
			"updatezone",
			"Updates the custom island zone.",
		));

		$this->registerSubCommand(new Value(
			$this->plugin,
			"value",
			"View the value of an island."
		));

		$this->registerSubCommand(new Visit(
			$this->plugin,
			"visit",
			"Visit another SkyBlock Island!",
			["tour"]
		));

		$this->registerSubCommand(new ZoneTools(
			$this->plugin,
			"zonetools",
			"Gives Custom Island Creator Tools",
			["zt", "zonetool"]
		));
	}

	public function onRun(CommandSender $sender, string $aliasused, array $args): void {

		if (isset($args["help"])) {

			$sender->sendMessage("Success!"); //proof of concept
			return;
		} else {

			$this->sendUsage();
			return;
		}
	}
}
