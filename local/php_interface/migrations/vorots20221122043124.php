<?php

namespace Sprint\Migration;


use Bitrix\Main\Loader;

class vorots20221122043124 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.2";

    /**
     * @throws Exceptions\ExchangeException
     * @throws Exceptions\RestartException
     * @return bool|void
     */
    public function up()
    {
        Loader::includeModule('iblock');
        $this->getExchangeManager()
            ->IblockElementsImport()
            ->setExchangeResource('iblock_elements.xml')
            ->setLimit(20)
            ->execute(function ($item) {
                $item['iblock_id'] = 25;
                $db = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => 25, 'SECTION_ID' => 269, 'INCLUDE_SUBSECTION' => 'Y'));
                $el = new \CIBlockElement();
                while ($ar = $db->Fetch()){
                    if (strpos($ar['NAME'], 'Комплект ворот секционных из панелей DoorHan (подъемного типа)') === false) {
                        $el->Update($ar['ID'], array('ACTIVE' => 'N'));
                        continue;
                    }
                    $name = str_replace(
                        'Комплект ворот секционных из панелей DoorHan (подъемного типа)',
                        'Секционные ворота DoorHan',
                        $ar['NAME']
                    );
                    $el->Update($ar['ID'], array('ACTIVE' => 'Y'));
                    $item['fields']['CODE'] = $ar['CODE'];
                    $item['fields']['NAME'] = $name;
                    $item['fields']['XML_ID'] = $ar['XML_ID'];
                    $item['fields']['IBLOCK_SECTION'] = array($ar['IBLOCK_SECTION_ID']);
                    $this->getHelperManager()
                        ->Iblock()
                        ->saveElement(
                            $item['iblock_id'],
                            $item['fields'],
                            $item['properties']
                        );
                }
            });
    }

    public function down()
    {
        //your code ...
    }
}
