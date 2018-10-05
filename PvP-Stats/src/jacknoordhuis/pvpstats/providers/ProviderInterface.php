<?php

/*
 * PvP-Stats plugin for PocketMine-MP
 * Copyright (C) 2014 Jack Noordhuis (CrazedMiner) 
 * <https://github.com/CrazedMiner/PocketMine-MP-Plugins/tree/master/PvP-Stats>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

namespace jacknoordhuis\pvpstats\providers;

use pocketmine\IPlayer;

interface ProviderInterface {

    public function getPlayer(IPlayer $player);
    
    public function getData($player);
    
    public function playerExists(IPlayer $player);
    
    public function addPlayer(IPlayer $player);
    
    public function removePlayer(IPlayer $player);
    
    public function updatePlayer(IPlayer $player, $type);
    
    public function close();

}
