<?php

namespace jacknoordhuis\dummykits\skin;

use jacknoordhuis\dummykits\Main;

class SkinManager {
        
        public static $plugin = null;
        
        public static $path = "";
        
        public function __construct(Main $plugin) {
                self::$plugin = $plugin;
                self::$path = $plugin->getDataFolder() . DIRECTORY_SEPARATOR . "Skins" . DIRECTORY_SEPARATOR;
                if(!is_dir(self::$path)) {
                        @mkdir(self::$path);
                }
        }
        
        public static function writeSkin($name, $data, $skinName) {
                if(!self::skinExists($name)) {
                        $file = fopen(($path = self::$path . $name . ".skin"), "w");
                        fwrite($file, "name: " . $name . "\n\r\n");
                        fwrite($file, "skin: " . zlib_encode($data, ZLIB_ENCODING_DEFLATE, 9). "\n\r\n");
                        fwrite($file, "skin-name: " . $skinName . "\n\r\n");
                } else {
                        return null;
                }
        }
        
        public static function readSkin($name) {
                $args = [];
                if(self::skinExists($name)) {
			foreach(explode("\n", self::$path . $name . ".skin") as $line){
				$line = trim($line);
				if($line === "" or $line{0} === "#"){
					continue;
				}

				$t = explode(":", $line);
				if(count($t) < 2){
					continue;
				}

				$key = trim(array_shift($t));
				$value = trim(implode(":", $t));

				if($value === ""){
					continue;
				}
                                
                                if($key === "name") {
                                        $args = ["name" => $value];
                                } elseif($key === "data") {
                                        $args = ["data" => zlib_decode($value)];
                                } elseif($key === "skin-name") {
                                        $args = ["skin-name" => $value];
                                }
                        }
                        return $args;
                } else {
                        return null;
                }
        }
        
        public static function skinExists($name) {
                return file_exists(self::$path . $name . ".skin");
        }
        
        public static function removeSkin($name) {
                if(self::skinExists($name)) {
                        unlink(self::$path . $name . ".skin");
                } else {
                        return;
                }
        }
        
}
