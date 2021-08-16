<?php
declare(strict_types=1);

namespace Kkevin14\TimeLimitItem;

use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;

class EventListener implements Listener
{
    /** @var TimeLimitItem */
    public TimeLimitItem $owner;

    /**
     * @param TimeLimitItem $owner
     */
    public function __construct(TimeLimitItem $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @param PlayerItemHeldEvent $event
     * @priority HIGHEST
     */
    public function onPlayerItemHeld(PlayerItemHeldEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if($item->getNamedTagEntry('time_item') !== null){
            $time = $item->getNamedTagEntry('time_item')->getValue();
            if($time < $_SERVER['REQUEST_TIME']){
                $player->getInventory()->removeItem($item);
                $this->owner->msg($player, '아이템의 기간이 만료되어 삭제되었습니다.');
            }
        }
    }

    public function onItemMove(InventoryTransactionEvent $event): void
    {
        $transaction = $event->getTransaction();
        $player = $transaction->getSource();
        foreach($transaction->getInventories() as $inventory){
            foreach($inventory->getContents() as $item){
                if($item->getNamedTagEntry('time_item') !== null){
                    $time = $item->getNamedTagEntry('time_item')->getValue();
                    if($time < $_SERVER['REQUEST_TIME']){
                        $inventory->removeItem($item);
                        $this->owner->msg($player, '아이템의 기간이 만료되어 삭제되었습니다.');
                    }
                }
            }
        }
    }
}