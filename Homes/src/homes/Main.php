<?php

/**
 * Main.php class
 *
 * Created on 7/05/2016 at 11:20 PM
 *
 * @author Jack
 */

namespace homes;

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
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!is_dir($this->getDataFolder()) . self::DATA_FOLDER) {
			@mkdir($this->getDataFolder() . self::DATA_FOLDER);
		}
		$this->getLogger()->info(TextFormat::AQUA . $this->getDescription()->getName() . TextFormat::GOLD . " v" . $this->getDescription()->getVersion() . TextFormat::GREEN . " by " . TextFormat::GOLD . $this->getDescription()->getAuthors()[0] . TextFormat::GREEN . " has been enabled!" . TextFormat::RESET);
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
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		if($sender instanceof Player) {
			switch(strtolower($command->getName())) {
				case "home":
					if($this->hasHome($sender->getName())) {
						$sender->teleport($this->getHome($sender->getName()));
						$sender->sendMessage(TextFormat::GREEN . "You have been teleported!");
						return true;
					} else {
						$sender->sendMessage(TextFormat::RED . "You have no home set!");
						return true;
					}
					break;
				case "sethome":
					$this->saveHome($sender->getName(), clone $sender->getPosition());
					$sender->sendMessage(TextFormat::GREEN . "Home saved!");
					return true;
					break;
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
	public function getHome($name) {
		$data = json_decode(file_get_contents($this->getDataFolder() . self::DATA_FOLDER . strtolower($name) . ".json"));
		return new Vector3($data->x, $data->y, $data->z);
	}

	/**
	 * Save a players home
	 *
	 * @param $name
	 * @param Vector3 $pos
	 */
	public function saveHome($name, Vector3 $pos) {
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
	public function hasHome($name) {
		return is_file($this->getDataFolder() . self::DATA_FOLDER . strtolower($name) . ".json");
	}

}