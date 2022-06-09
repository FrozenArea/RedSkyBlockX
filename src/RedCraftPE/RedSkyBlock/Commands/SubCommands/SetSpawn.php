<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class SetSpawn {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onSetSpawnCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("redskyblock.setspawn")) {

      $plugin = $this->plugin;

      $owner = $plugin->getIslandAtPlayer($sender);
      $senderName = strtolower($sender->getName());
      $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
      $filePath = $plugin->getDataFolder() . "Players/" . strtolower($sender->getName()) . ".json";

      if (array_key_exists($senderName, $skyblockArray)) {

        if (strtolower($sender->getName()) === $owner && $sender->getWorld()->getFolderName() === $plugin->skyblock->get("Master World")) {

          $playerDataEncoded = file_get_contents($filePath);
          $playerData = (array) json_decode($playerDataEncoded, true);

          $playerData["Island Spawn"][0] = (int) round($sender->getPosition()->x);
          $playerData["Island Spawn"][1] = (int) round($sender->getPosition()->y);
          $playerData["Island Spawn"][2] = (int) round($sender->getPosition()->z);

          $playerDataEncoded = json_encode($playerData);
          file_put_contents($filePath, $playerDataEncoded);
          $sender->sendMessage(TextFormat::GREEN . "Your island spawn point has been set to " . TextFormat::WHITE . (int) round($sender->getPosition()->x) . TextFormat::GREEN . ", " . TextFormat::WHITE . (int) round($sender->getPosition()->y) . TextFormat::GREEN . ", " . TextFormat::WHITE . (int) round($sender->getPosition()->z) . TextFormat::GREEN . ".");
          return true;
        } else {

          $sender->sendMessage(TextFormat::RED . "You must be on your island to set your island's spawn point.");
          return true;
        }
      } else {

        $sender->sendMessage(TextFormat::RED . "You have not created a SkyBlock island yet.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}
