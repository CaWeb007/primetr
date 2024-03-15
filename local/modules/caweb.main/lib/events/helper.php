<?
namespace Caweb\Main\Events;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
class Helper{
    public static function getB24LeadTitle($iblockId){
        $name = IblockTable::getRowById($iblockId)['NAME'];
        return Loc::getMessage('B24_LEAD_TITLE_PREFIX').$name;
    }
}
