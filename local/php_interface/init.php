<?

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
function Pr($x){
    echo '<pre>';var_dump($x);echo '</pre><hr>';
}
Loader::includeModule('caweb.main');

EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'sendBitrix24'));
