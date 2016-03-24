<?php

/**
 * AutoInv Main class
 * 
 * Created on Mar 24, 2016 at 10:09:00 PM
 *
 * @author Jack
 */

namespace autoinv;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {
        
        /** $listener EventListener */
        public $listener;
        
        public function onEnable() {
                $this->setListener();
        }
        
        /**
         * Set the event listener
         * 
         * @return null
         */
        public function setListener() {
                if(!$this->listener instanceof EventListener) {
                        $this->listener = new EventListener($this);
                }
                return;
        }
        
        /**
         * Get the event listener
         * 
         * @return null|EventListener
         */
        public function getListener() {
                return $this->listener;
        }
        
}
