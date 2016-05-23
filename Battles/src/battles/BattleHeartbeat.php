<?php

/**
 * BattleHeartbeat.php class
 *
 * Created on 23/05/2016 at 11:04 PM
 *
 * @author Jack
 */


namespace battles;

use pocketmine\scheduler\PluginTask;

class BattleHeartbeat extends PluginTask {
	
	/** @var Main */
	private $plugin;

	public function __construct(Main $plugin) {
		parent::__construct($plugin);
		$this->plugin = $plugin;
	}

	/**
	 * @return Main
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	/**
	 * Ticks all battles
	 * 
	 * @param $tick
	 */
	public function onRun($tick) {
		foreach($this->plugin->getBattles() as $battle) {
			if(!$battle->hasEnded()) {
				$battle->tick($tick);
			}
		}
	}

}