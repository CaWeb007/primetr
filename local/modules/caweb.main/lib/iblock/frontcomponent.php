<?php
namespace Caweb\Main\Iblock;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
class FrontComponent {
    private static $SHOW_PROPERTY = 'UF_SHOW_FRONT';
    private static $SORT_PROPERTY = 'UF_FRONT_SORT';
    private static $IBLOCK_ID = 20;
    private static $ITEMS_COUNT = 12;
    private $sectionItems = array();
    private $elementItems = array();
    private $migrateLog = array();
    protected static $instance = null;
    public static function getInstance(){
        if (!empty(self::$instance)) return self::$instance;
        return self::$instance = new self();
    }
    private function __construct() {
        Loader::includeModule('iblock');
        Loader::includeModule('aspro.stroy');
    }
    public function getItems(){
        $this->setElementItems();
        $this->setSectionItems();
        return $this->getSortItems();
    }
    private function setSectionItems(){
        $result = array();
        $filter = array(
            'IBLOCK_ID' => self::$IBLOCK_ID,
            'ACTIVE' => 'Y',
            self::$SHOW_PROPERTY => 1
        );
        $select = array('ID', 'IBLOCK_ID', 'NAME', 'PICTURE', 'DETAIL_PICTURE', 'SECTION_PAGE_URL',
            self::$SHOW_PROPERTY, self::$SORT_PROPERTY);
        $cacheClass = new \CCache();
        $dbRes = $cacheClass->CIBlockSection_GetList(array(), $filter, false, $select);
        foreach ($dbRes as $arRes){
            if (empty($arRes[self::$SORT_PROPERTY])) continue;
            $result[(int)$arRes[self::$SORT_PROPERTY]] = $arRes;
        }
        $this->sectionItems = $result;
    }
    private function setElementItems(){
        $result = array();
        $filter = array(
            'IBLOCK_ID' => self::$IBLOCK_ID,
            'PROPERTY_'.self::$SHOW_PROPERTY.'_VALUE' => Loc::getMessage('CAWEB_FC_YES'),
            'ACTIVE' => 'Y'
        );
        $select = array('ID', 'IBLOCK_ID', 'NAME', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL',
            'PROPERTY_'.self::$SHOW_PROPERTY, 'PROPERTY_'.self::$SORT_PROPERTY);
        $cacheClass = new \CCache();
        $dbRes = $cacheClass->CIBlockElement_GetList(array(), $filter, false, false, $select);
        foreach ($dbRes as $arRes){
            if (empty($arRes['PROPERTY_'.self::$SORT_PROPERTY.'_VALUE'])) continue;
            $result[(int)$arRes['PROPERTY_'.self::$SORT_PROPERTY.'_VALUE']] = $arRes;
            $result[(int)$arRes['PROPERTY_'.self::$SORT_PROPERTY.'_VALUE']]['SECTION_PAGE_URL'] = $arRes['DETAIL_PAGE_URL'];
            $result[(int)$arRes['PROPERTY_'.self::$SORT_PROPERTY.'_VALUE']]['PICTURE'] =
                (!empty($arRes['PREVIEW_PICTURE']))? $arRes['PREVIEW_PICTURE']: $arRes['DETAIL_PICTURE'];
        }
        $this->elementItems = $result;
    }
    private function getSortItems(){
        $result = array();
        $result = $this->elementItems + $this->sectionItems;
        ksort($result, SORT_NUMERIC);
        $result = array_slice($result, 0, self::$ITEMS_COUNT, true);
        return $result;
    }
    /**\Caweb\Main\Iblock\FrontComponent::getInstance()->dbMigrate();*/
    public function dbMigrate(){
        $this->sectionDbMigrate();
        $this->elementDbMigrate();
        var_dump($this->migrateLog);
    }
    private function sectionDbMigrate(){
        global $APPLICATION;
        global $USER_FIELD_MANAGER;
        $object = new \CUserTypeEntity();
        $entity = 'IBLOCK_'.self::$IBLOCK_ID.'_SECTION';
        $fields = array(
            'ENTITY_ID' => $entity,
            'FIELD_NAME' => self::$SHOW_PROPERTY,
            'USER_TYPE_ID' => 'boolean',
            'SORT' => 550,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => array(
                'DEFAULT_VALUE' => 0,
                'DISPLAY' => 'CHECKBOX',
                'LABEL' => array(
                    '0' => '',
                    '1' => ''
                )
            ),
            'EDIT_FORM_LABEL' => array('ru' => Loc::getMessage('CAWEB_FCM_SHOW_MESS')),
            'LIST_COLUMN_LABEL' => array('ru' => Loc::getMessage('CAWEB_FCM_SHOW_MESS')),
            'ERROR_MESSAGE' => array('ru' => Loc::getMessage('CAWEB_FCM_SHOW_MESS')),
            'HELP_MESSAGE' => array('ru' => Loc::getMessage('CAWEB_FCM_SHOW_MESS'))
        );
        if(!($object->Add($fields))) $this->migrateLog[] = $APPLICATION->GetException()->GetString();
        else $this->migrateLog[] = Loc::getMessage('CAWEB_FCM_CREATE_PROP', array('#CODE#' => $fields['FIELD_NAME'], '#OBJECT#' => $entity));
        $fields = array(
            'ENTITY_ID' => $entity,
            'FIELD_NAME' => self::$SORT_PROPERTY,
            'USER_TYPE_ID' => 'integer',
            'SORT' => 551,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => array(
                'DEFAULT_VALUE' => 100,
                'SIZE' => 3,
            ),
            'EDIT_FORM_LABEL' => array('ru' => Loc::getMessage('CAWEB_FCM_SORT_MESS')),
            'LIST_COLUMN_LABEL' => array('ru' => Loc::getMessage('CAWEB_FCM_SORT_MESS')),
            'ERROR_MESSAGE' => array('ru' => Loc::getMessage('CAWEB_FCM_SORT_MESS')),
            'HELP_MESSAGE' => array('ru' => Loc::getMessage('CAWEB_FCM_SORT_HELP'))
        );
        if(!($object->Add($fields))) $this->migrateLog[] = $APPLICATION->GetException()->GetString();
        else $this->migrateLog[] = Loc::getMessage('CAWEB_FCM_CREATE_PROP', array('#CODE#' => $fields['FIELD_NAME'], '#OBJECT#' => $entity));
        $dbSections = \CIBlockSection::GetList(array(), array('IBLOCK_ID' => self::$IBLOCK_ID), false, array('ID', 'IBLOCK_ID', 'NAME', self::$SHOW_PROPERTY, self::$SORT_PROPERTY));
        $settings = array(278 => 9, 16 => 10, 15 => 11, 17 => 12);
        while ($arSections = $dbSections->GetNext()){
            $sectionId = (int)$arSections['ID'];
            $updateField = 0;
            if (array_key_exists($sectionId, $settings)) $updateField = 1;
            $res = $USER_FIELD_MANAGER->Update($entity, $sectionId, array(self::$SHOW_PROPERTY => $updateField));
            if ($res) $this->migrateLog[] = Loc::getMessage('CAWEB_FCM_SHOW_OK', array('#NAME#' => $arSections['NAME'], '#FIELD#' => $updateField));
            else $this->migrateLog[] = Loc::getMessage('CAWEB_FCM_SHOW_ERROR', array('#NAME#' => $arSections['NAME']));
            if ($updateField === 1){
                $res = $USER_FIELD_MANAGER->Update($entity, $sectionId, array(self::$SORT_PROPERTY => $settings[$sectionId]));
                if ($res) $this->migrateLog[] = Loc::getMessage('CAWEB_FCM_SORT_OK', array('#NAME#' => $arSections['NAME'], '#FIELD#' => $settings[$sectionId]));
                else $this->migrateLog[] = Loc::getMessage('CAWEB_FCM_SORT_ERROR', array('#NAME#' => $arSections['NAME']));
            }
        }

    }
    private function elementDbMigrate(){
        $object = new \CIBlockProperty();
        $field = array(
            'IBLOCK_ID' => self::$IBLOCK_ID,
            'NAME' => Loc::getMessage('CAWEB_FCM_SHOW_MESS'),
            'SORT' => 550,
            'CODE' => self::$SHOW_PROPERTY,
            'PROPERTY_TYPE' => 'L',
            'ROW_COUNT' => 1,
            'COL_COUNT' => 30,
            'LIST_TYPE' => 'C',
            'MULTIPLE' => 'N',
            'VALUES' => array(
                array(
                    'VALUE' => Loc::getMessage('CAWEB_FC_YES'),
                    'DEF' => 'N',
                    'SORT' => 501,
                    'XML_ID' => 'YES'
                ),
                array(
                    'VALUE' => Loc::getMessage('CAWEB_FC_NO'),
                    'DEF' => 'Y',
                    'SORT' => 501,
                    'XML_ID' => 'NO'
                )
            )
        );
        if ($object::GetByID($field['CODE'], self::$IBLOCK_ID)->Fetch()){
            $this->migrateLog[] = Loc::getMessage('CAWEB_FCM_EXIST_PROP', array('#CODE#' => $field['CODE'], '#OBJECT#' => 'IBLOCK'));
        }else{
            $res = $object->Add($field);
            if (!$res) $this->migrateLog[] = $object->LAST_ERROR;
            else $this->migrateLog[] = Loc::getMessage('CAWEB_FCM_CREATE_PROP', array('#CODE#' => $field['CODE'], '#OBJECT#' => 'IBLOCK'));
        }

        $field = array(
            'IBLOCK_ID' => self::$IBLOCK_ID,
            'NAME' => Loc::getMessage('CAWEB_FCM_SORT_MESS'),
            'SORT' => 500,
            'CODE' => self::$SORT_PROPERTY,
            'PROPERTY_TYPE' => 'N',
            'ROW_COUNT' => 1,
            'COL_COUNT' => 3,
            'LIST_TYPE' => 'L',
            'MULTIPLE' => 'N'
        );
        if ($object::GetByID($field['CODE'], self::$IBLOCK_ID)->Fetch()){
            $this->migrateLog[] = Loc::getMessage('CAWEB_FCM_EXIST_PROP', array('#CODE#' => $field['CODE'], '#OBJECT#' => 'IBLOCK'));
        }else{
            $res = $object->Add($field);
            if (!$res) $this->migrateLog[] = $object->LAST_ERROR;
            else $this->migrateLog[] = Loc::getMessage('CAWEB_FCM_CREATE_PROP', array('#CODE#' => $field['CODE'], '#OBJECT#' => 'IBLOCK'));
        }
        $enumYesId = (int)\CIBlockPropertyEnum::GetList(array(), array('CODE' => self::$SHOW_PROPERTY, 'XML_ID' => 'YES'))->Fetch()['ID'];
        $enumNoId = (int)\CIBlockPropertyEnum::GetList(array(), array('CODE' => self::$SHOW_PROPERTY, 'XML_ID' => 'NO'))->Fetch()['ID'];
        $dbElements = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => self::$IBLOCK_ID));
        $settings = array(38 => 5, 43 => 1, 6564 => 8, 7271 => 7, 1554 => 6, 7031 => 4, 1536 => 3, 1537 => 2);
        while($arElement = $dbElements->GetNext()){
            $elementId = (int)$arElement['ID'];
            $updateField = array('ELEMENT_ID' => $elementId, 'IBLOCK_ID' => self::$IBLOCK_ID);
            $updateField[self::$SHOW_PROPERTY] = $enumNoId;
            if (array_key_exists($elementId, $settings)){
                $updateField[self::$SHOW_PROPERTY] = $enumYesId;
                $updateField[self::$SORT_PROPERTY] = $settings[$elementId];
            }
            \CIBlockElement::SetPropertyValuesEx($elementId, self::$IBLOCK_ID, $updateField);
        }
    }
}