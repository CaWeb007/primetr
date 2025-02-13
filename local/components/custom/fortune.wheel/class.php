<?
use Bitrix\Main\Context;
use Bitrix\Main\Web\Cookie;
use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;

class FortuneWheelComponent extends \CBitrixComponent implements Controllerable {

    const PRIZE_IBLOCK_CODE = 'prizes';
    const RESULT_IBLOCK_CODE = 'aspro_stroy_fortune';

    public function onPrepareComponentParams($arParams){

        if ($arParams['FIRST_START'] === 'Y'){
            $arParams['PRIZE_IBLOCK_ID'] = self::createPrizesInfoblock();
            $arParams['RESULT_IBLOCK_ID'] = self::createFormInfoblock();
        }
        return $arParams;
    }
    public function executeComponent(){
        Loader::includeModule('iblock');
        $this->arResult['PRIZES'] = $this->getPrizesFromInfoblock();
        $this->arResult['COOKIE_PRIZE'] = $this->getCookie();
        $this->arResult['RESULT_IBLOCK_ID'] = $this->arParams['RESULT_IBLOCK_ID'];
        $this->includeComponentTemplate();
    }
    public function configureActions(){
        return [
            'saveResult' => [
                'prefilters' => [],
            ],
            'saveStatus' => [
                'prefilters'=> []
            ]
        ];
    }
    public function saveStatusAction($prize){
        $this->setCookie(array('STATUS' => 'PRIZE', 'PRIZE' => $prize));
    }
    public function saveResultAction($phone, $prize, $iblockId){
        $result = $this->sendIblock($phone, $prize, $iblockId);
        if ($result) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Ошибка'];
        }
    }

    private function createIblock($arFields){
        $iblockId = CIBlock::GetList([], ['CODE' => $arFields['IBLOCK']['CODE']])->Fetch()['ID'];
        if ($iblockId) return $iblockId;
        $iblockFields = [
            'ACTIVE' => 'Y',
            'NAME' => $arFields['IBLOCK']['NAME'],
            'CODE' => $arFields['IBLOCK']['CODE'],
            'IBLOCK_TYPE_ID' => $arFields['IBLOCK']['IBLOCK_TYPE_ID'],
            'SITE_ID' => ['s1'],
            'GROUP_ID' => ['2' => 'R'],
        ];
        $obIblock = new \CIBlock();
        $iblockId = $obIblock->Add($iblockFields);
        if (!$iblockId) throw new Exception('Ошибка при создании инфоблока: ' . $obIblock->LAST_ERROR);

        $obProperty = new CIBlockProperty;
        foreach ($arFields['PROPERTIES'] as $item) {
            $item['IBLOCK_ID'] = $iblockId;
            $propertyId = $obProperty->Add($item);
            if (!$propertyId) throw new Exception('Ошибка при создании свойства: ' . $obProperty->LAST_ERROR);
        }
        return $iblockId;
    }
    private function createFormInfoblock() {
        $arFields['IBLOCK'] = [
            'NAME' => 'Колесо Фортуны',
            'CODE' => self::RESULT_IBLOCK_CODE,
            'IBLOCK_TYPE_ID' => 'aspro_stroy_form',
        ];
        $arFields['PROPERTIES'] = array(
            [
                'NAME' => 'Контактный телефон',
                'CODE' => 'PHONE',
                'PROPERTY_TYPE' => 'N',
                'IS_REQUIRED' => 'Y',
            ],
            [
                'NAME' => 'Текст обращения',
                'CODE' => 'MESSAGE',
                'PROPERTY_TYPE' => 'S',
                'IS_REQUIRED' => 'Y',
            ]
        );
        return $this->createIblock($arFields);
    }
    private function createPrizesInfoblock(){
        $arFields['IBLOCK'] = [
            'NAME' => 'Призы для Колеса Фортуны',
            'CODE' => self::PRIZE_IBLOCK_CODE,
            'IBLOCK_TYPE_ID' => 'aspro_stroy_content',
        ];
        $arFields['PROPERTIES'] = array(
            [
                'NAME' => 'Вероятность',
                'CODE' => 'PROBABILITY',
                'PROPERTY_TYPE' => 'N',
                'IS_REQUIRED' => 'Y',
            ]
        );
        return $this->createIblock($arFields);
    }
    private function getCookie() {
        $request = Context::getCurrent()->getRequest();
        return unserialize($request->getCookie('FORTUNE_WHEEL'));
    }
    private function getPrizesFromInfoblock(){
        $prizes = [];
        $filter = ['IBLOCK_ID' => $this->arParams['PRIZE_IBLOCK_ID'], 'ACTIVE' => 'Y'];
        $select = ['ID', 'NAME', 'PROPERTY_PROBABILITY'];

        $dbItems = CIBlockElement::GetList([], $filter, false, false, $select);
        while ($item = $dbItems->Fetch()) {
            $prizes[] = [
                'name' => $item['NAME'],
                'probability' => (int)$item['PROPERTY_PROBABILITY_VALUE'],
            ];
        }
        return $prizes;
    }
    private function sendIblock($phone, $prize, $iblockId){
        Loader::includeModule('iblock');
        $arFields = array(
            'NAME' => 'Заявка '.$phone,
            'IBLOCK_ID' => $iblockId,
            'PROPERTY_VALUES' => array(
                'PHONE' => $phone,
                'MESSAGE' => "Выигранный приз: $prize"
            )
        );
        $obElement = new \CIBlockElement();
        $elementId = $obElement->Add($arFields);
        if (!$elementId) return false;
        $this->setCookie(array('STATUS' => 'END', 'PRIZE' => $prize));
        return true;
    }
    private function setCookie($value) {
        $context = Context::getCurrent();
        $cookie = new Cookie('FORTUNE_WHEEL', serialize($value), time() + 60*60*24*30);
        $cookie->setHttpOnly(false);
        $cookie->setSecure(false);
        $context->getResponse()->addCookie($cookie);
        $context->getResponse()->flush("");
    }
}