<?php

namespace dummykits\entity;

interface Dummy {
        
        public function setCustomName($name);
        
        public function setCustomDescription($string);
        
        public function addCommand($string);
        
        public function addKit($string);
        
        public function setMove($value = true);
        
        public function setKnockback($value = true);
        
}
