<?php

/**
 * Main.php class
 *
 * Created on 23/05/2016 at 10:44 PM
 *
 * @author Jack
 */

namespace battles;

use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

/**
 * Simple battles plugin
 */
class Main extends PluginBase {

	/** @var array */
	private $arenaData = [];

	/** @var Arena[] */
	private $arenas = [];

	/** @var Battle[] */
	private $battles = [];

	public function onEnable() {
		$this->saveResource("arenas.yml");
		$this->arenaData = (new Config($this->getDataFolder() . "arenas.yml", Config::YAML))->getAll()["arenas"];
	}

	/**
	 * Loads the arena data into arena classes
	 */
	public function loadArenas() {
		foreach($this->arenaData as $arena) {
			$this->arenas[] = new Arena($this, $arena["name"], self::parseVectors($arena["spawns"]));
		}
	}

	/**
	 * @return Battle[]
	 */
	public function getBattles() {
		return $this->battles;
	}

	/**
	 * Get a vector from a string \o/
	 *
	 * @param $string
	 * @return Vector3
	 */
	public static function parseVector($string) {
		$temp = explode(",", str_replace(" ", "", $string));
		return new Vector3($temp[0], $temp[1], $temp[2]);
	}

	public static function parseVectors(array $strings) {
		$vectors = [];
		foreach($strings as $string) {
			$vectors[] = self::parseVector($string);
		}
		return $vectors;
	}

}