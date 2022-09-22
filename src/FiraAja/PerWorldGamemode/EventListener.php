<?php

namespace FiraAja\PerWorldGamemode;

use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class EventListener implements Listener {

    /* @var Main $plugin */
    private Main $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onTeleport(EntityTeleportEvent $event){
        $player = $event->getEntity();
        if(!$player instanceof Player) return;
        $world = $event->getTo()->getWorld();
        if(!$this->plugin->getServer()->getWorldManager()->isWorldLoaded($world->getFolderName())){
            $this->plugin->getServer()->getWorldManager()->loadWorld($world->getFolderName());
            return;
        }
        if(!$this->plugin->getServer()->getWorldManager()->isWorldGenerated($world->getFolderName())){
            $this->plugin->getServer()->getWorldManager()->generateWorld($world->getFolderName());
            return;
        }
        if(!in_array($world->getFolderName(), array_keys($this->plugin->config->get("worlds")))){
            $player->setGamemode(GameMode::SURVIVAL());
            return;
        }
        if(!isset($this->plugin->config->get("worlds")[$world->getFolderName()])){
            return;
        }
        $player->setGamemode(GameMode::fromString($this->plugin->config->get("worlds")[$world->getFolderName()]["gamemode"]));
    }
}