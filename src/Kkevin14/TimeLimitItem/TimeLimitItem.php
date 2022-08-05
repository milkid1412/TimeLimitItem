<?php
declare(strict_types=1);

namespace Kkevin14\TimeLimitItem;

use Kkevin14\TimeLimitItem\command\MainCommand;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class TimeLimitItem extends PluginBase
{
    protected function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register('Kkevin14', new MainCommand($this));
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    public function msg(Player $player, string $msg): void
    {
        $player->sendMessage('§e< §f서버 §e> §7' . $msg);
    }
}
