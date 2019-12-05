<?

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;

Loader::includeModule('caweb.main');

EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'sendBitrix24'));
