<?php

/*
 * The MIT License
 *
 * Copyright 2015 Jack Noordhuis (CrazedMiner) CrazedMiner.weebly.com.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace HealthStatus;

use pocketmine\event\Listener;
use EssentialsPE\Events\PlayerNickChangeEvent;

use HealthStatus\Main;

class NickChange implements Listener {

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }
    
    public function onNickChange(PlayerNickChangeEvent $event) {
        $this->plugin->broadcastMessage("A Players Nick has changed!");
        $this->player = $event->getPlayer();
        $this->config = $this->plugin->getConfig()->getAll();
        if($this->config["Nametag"]["Enabled"] === true) {
            $this->plugin->getServer()->getScheduler()->scheduleDelayedTask(new Task($this, $this->player), 1);
        }
    }

}
