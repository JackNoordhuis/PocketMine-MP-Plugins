<?php

/**
 * Arena.php class
 *
 * Created on 23/05/2016 at 10:45 PM
 *
 * @author Jack
 */


namespace battles;
use pocketmine\math\Vector3;

/**
 * Basic arena for battles
 */
class Arena{

	/** @var string */
	private $name = "";

	/** @var Vector3[] */
	private $spawns;

	/**
	 * Arena constructor
	 *
	 * @param Main $plugin
	 * @param $name
	 * @param array $spawns
	 */
	public function __construct(Main $plugin, $name, array $spawns) {
		$this->name = $name;
		$this->spawns = $spawns;
	}

	/**
	 * @return Vector3
	 */
	public function getRandomSpawn() {
		return $this->spawns[array_rand($this->spawns)];
	}

}