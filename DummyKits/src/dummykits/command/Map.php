<?php

namespace dummykits\command;

use pocketmine\command\CommandMap;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use dummykits\command\commands\AddDummy;

use dummykits\Main;

class Map implements CommandMap {
        
        private $plugin = null;
        
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
        }
        
        public function setDefaultCommands() {
                $this->register("dummykits", new AddDummy("adddummy"));
        }
        
        public function registerAll($fallbackPrefix, Command $commands) {
                
        }
        
        public function register($fallbackPrefix, Command $command, $label = null) {
                if($label === null) {
                        $label = $command->getName();
                }
                $label = strtolower(trim($label));
                $fallbackPrefix = strtolower(trim($fallbackPrefix));
                
                $registered = $this->registerAlias($command, false, $fallbackPrefix, $label);

                $aliases = $command->getAliases();
                foreach($aliases as $index => $alias) {
                        if(!$this->registerAlias($command, true, $fallbackPrefix, $alias)) {
                                unset($aliases[$index]);
                        }
                }
                $command->setAliases($aliases);

                if(!$registered) {
                        $command->setLabel($fallbackPrefix . ":" . $label);
                }

                $command->register($this);

                return $registered;
        }
        
        public function registerAlias(Command $command, $isAlias, $fallbackPrefix, $label) {
                $this->knownCommands[$fallbackPrefix . ":" . $label] = $command;
                if(($command instanceof VanillaCommand or $isAlias) and isset($this->knownCommands[$label])) {
                        return false;
                }

                if(isset($this->knownCommands[$label]) and $this->knownCommands[$label]->getLabel() !== null and $this->knownCommands[$label]->getLabel() === $label) {
                        return false;
                }

                if(!$isAlias) {
                        $command->setLabel($label);
                }

                $this->knownCommands[$label] = $command;

                return true;
        }
        
        public function dispatch(CommandSender $sender, $cmdLine) {
                $args = explode(" ", $commandLine);

                if(count($args) === 0) {
                        return false;
                }

                $sentCommandLabel = strtolower(array_shift($args));
                $target = $this->getCommand($sentCommandLabel);

                if($target === null) {
                        return false;
                }

                $target->timings->startTiming();
                try {
                        $target->execute($sender, $sentCommandLabel, $args);
                } catch (\Exception $e) {
                        $sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.exception"));
                        $this->server->getLogger()->critical($this->server->getLanguage()->translateString("pocketmine.command.exception", [$commandLine, (string) $target, $e->getMessage()]));
                        $logger = $sender->getServer()->getLogger();
                        if($logger instanceof MainLogger) {
                                $logger->logException($e);
                        }
                }
                $target->timings->stopTiming();

                return true;
        }
        
        public function clearCommands() {
                foreach($this->knownCommands as $command) {
                        $command->unregister($this);
                }
                $this->knownCommands = [];
                $this->setDefaultCommands();
        }
        
        public function getCommand($name) {
                if(isset($this->knownCommands[$name])) {
                        return $this->knownCommands[$name];
                }

                return null;
        }
        
        public function getCommands() {
                return $this->knownCommands;
        }
        
        public function registerServerAliases() {
                $values = $this->server->getCommandAliases();

                foreach($values as $alias => $commandStrings) {
                        if(strpos($alias, ":") !== false or strpos($alias, " ") !== false) {
                                $this->server->getLogger()->warning($this->server->getLanguage()->translateString("pocketmine.command.alias.illegal", [$alias]));
                                continue;
                        }

                        $targets = [];

                        $bad = "";
                        foreach($commandStrings as $commandString) {
                                $args = explode(" ", $commandString);
                                $command = $this->getCommand($args[0]);

                                if($command === null) {
                                        if(strlen($bad) > 0) {
                                                $bad .= ", ";
                                        }
                                        $bad .= $commandString;
                                } else {
                                        $targets[] = $commandString;
                                }
                        }

                        if(strlen($bad) > 0) {
                                $this->server->getLogger()->warning($this->server->getLanguage()->translateString("pocketmine.command.alias.notFound", [$alias, $bad]));
                                continue;
                        }

                        //These registered commands have absolute priority
                        if(count($targets) > 0) {
                                $this->knownCommands[strtolower($alias)] = new FormattedCommandAlias(strtolower($alias), $targets);
                        } else {
                                unset($this->knownCommands[strtolower($alias)]);
                        }
                }
        }
}
