<?
namespace Caweb\Main\Page;

use Bitrix\Main\Context;
use Bitrix\Main\Web\Uri;

global $APPLICATION;
class Helper {
    private $uri = null;
    private $path = null;
    private static $instance = null;
    private function __construct() {
        $this->uri = new Uri(Context::getCurrent()->getRequest()->getRequestUri());
        $this->path = $this->uri->getPath();
    }
    public static function getInstance(){
        if (!is_null(self::$instance)) return self::$instance;
        return self::$instance = new self();
    }
    public static function needSideMenu(){
        if (self::isPage('company/stock'))
            return false;
        else
            return ($APPLICATION->GetProperty('MENU') !== "N" ? true : false);
    }
    public static function isPage($subSection){
        return (strpos(self::getInstance()->path, $subSection) !== false);
    }
    public static function isDetailInCatalog() {
        global $APPLICATION;
        $url = $APPLICATION->GetCurPage(false);
        $code = array_pop(array_filter(explode( '/',  $url)));
        $rsElements = \CIBlockElement::GetList(array(),array('IBLOCK_ID' => 20, '=CODE' => $code));
        $res = $rsElements->Fetch() !== false;
        return $res;
    }
}