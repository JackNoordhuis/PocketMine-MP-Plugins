<?php

/**
 * Main.php – PocketMine-MP-Plugins
 *
 * Copyright (C) 2018 Jack Noordhuis
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Jack
 *
 */

namespace jacknoordhuis\homes;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

/**
 * Simple plugin that allows your players to set homes
 */
class Main extends PluginBase implements Listener {

	/**
	 * Folder in which the player data is stored
	 */
	const DATA_FOLDER = "players" . DIRECTORY_SEPARATOR;

	/**
	 * Handles the enabling for homes
	 */
	public function onEnable() {
		if(!is_dir($this->getDataFolder()) . self::DATA_FOLDER) {
			@mkdir($this->getDataFolder() . self::DATA_FOLDER);
		}

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * Handle all the command execution
	 *
	 * @param CommandSender $sender
	 * @param Command $command
	 * @param string $label
	 * @param array $args
	 *
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
		if($sender instanceof Player) {
			switch(strtolower($command->getName())) {
				case "home":
					if($this->hasHome($sender->getName())) {
						$sender->teleport($this->getHome($sender->getName()));
						$sender->sendMessage(TextFormat::GREEN . "You have been teleported!");
					} else {
						$sender->sendMessage(TextFormat::RED . "You have no home set!");
					}
					return true;
				case "sethome":
					$this->saveHome($sender->getName(), clone $sender->getPosition());
					$sender->sendMessage(TextFormat::GREEN . "Home saved!");
					return true;
			}
		} else {
			$sender->sendMessage(TextFormat::RED . "Please use this command in-game!");
			return true;
		}

		return false;
	}

	/**
	 * Get a players home
	 *
	 * @param $name
	 *
	 * @return Vector3
	 */
	public function getHome($name) : Vector3 {
		$data = json_decode(file_get_contents($this->getDataFolder() . self::DATA_FOLDER . strtolower($name) . ".json"));
		return new Vector3($data->x, $data->y, $data->z);
	}

	/**
	 * Save a players home
	 *
	 * @param $name
	 *
	 * @param Vector3 $pos
	 */
	public function saveHome($name, Vector3 $pos) : void {
		$file = fopen($this->getDataFolder() . self::DATA_FOLDER . strtolower($name) . ".json", "w");
		fwrite($file, json_encode([
			"x" => $pos->x,
			"y" => $pos->y,
			"z" => $pos->z
		]));
		fclose($file);
	}

	/**
	 * Check if a player has a home
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	public function hasHome($name) : bool {
		return is_file($this->getDataFolder() . self::DATA_FOLDER . strtolower($name) . ".json");
	}

}