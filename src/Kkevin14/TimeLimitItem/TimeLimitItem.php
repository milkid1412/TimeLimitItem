<?php
declare(strict_types=1);

namespace Kkevin14\TimeLimitItem;

use Kkevin14\TimeLimitItem\command\MainCommand;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class TimeLimitItem extends PluginBase
{
    public function onEnable()
    {
        $this->getServer()->getCommandMap()->register('Kkevin14', new MainCommand($this));
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    public function msg(Player $player, string $msg): void
    {
        $player->sendMessage('§e< §f서버 §e> §f' . $msg);
    }
}