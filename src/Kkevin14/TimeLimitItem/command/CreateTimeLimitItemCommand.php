<?php
declare(strict_types=1);

namespace Kkevin14\TimeLimitItem\command;

use Kkevin14\TimeLimitItem\TimeLimitItem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class CreateTimeLimitItemCommand extends Command
{
    /** @var TimeLimitItem */
    public TimeLimitItem $owner;

    /**
     * @param TimeLimitItem $owner
     */
    public function __construct(TimeLimitItem $owner)
    {
        parent::__construct('기간제', '기간제 아이템을 생성합니다.', '/기간제 (날짜): 날짜 형식은 YYYY:MM:DD:hh:ii:ss로 적어주세요.', ['기간제아이템', 'tli', 'timelimititem', 'timeitem', 'fti']);
        $this->owner = $owner;
        $this->setPermission('timeitem.command.createtimeitem');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!($sender instanceof Player && $this->testPermission($sender))) return;
        if(!isset($args[0])){
            $this->owner->msg($sender, '날짜를 설정해주세요!');
            return;
        }
        $item = $sender->getInventory()->getItemInHand();
        if($item->isNull()){
            $this->owner->msg($sender, '공기는 기간제아이템으로 추가할 수 없습니다.');
            return;
        }
        $date_array = explode(':', $args[0]);
        if((count($date_array) !== 6) || (strlen($date_array[0]) !== 4) || (strlen($date_array[1]) !== 2) || (strlen($date_array[2]) !== 2) || (strlen($date_array[3]) !== 2) || (strlen($date_array[4]) !== 2) || (strlen($date_array[5]) !== 2)){
            $this->owner->msg($sender, '날짜 형식은 YYYY:MM:DD:hh:ii:ss로 적어주세요.');
            return;
        }
        $time = mktime((int) $date_array[3], (int) $date_array[4], (int) $date_array[5], (int) $date_array[1], (int) $date_array[2], (int) $date_array[0]);
        if($time === false || $time > PHP_INT_MAX || $time < 0){
            $this->owner->msg($sender, '기간을 정확히 입력해주세요.');
            return;
        }
        $item->getNamedTag()->setInt('time_item', $time);
        $itemName = $item->hasCustomName() ? $item->getCustomName() : $item->getName();
        $item->setCustomName('§e[ §b기간제 §e] §r' . $itemName);
        $lore = $item->getLore();
        $lore[] = '';
        $lore[] = '§r§f이 아이템은 §e' . $date_array[0] . '년 ' . $date_array[1] . '월 ' . $date_array[2] . '일 ' . $date_array[3] . '시 ' . $date_array[4] . '분 ' . $date_array[5] . '초§f가 되면 §c소멸§f합니다.';
        $item->setLore($lore);
        $sender->getInventory()->setItemInHand($item);
        $this->owner->msg($sender, '기간제 아이템이 생성되었습니다!');
    }
}
