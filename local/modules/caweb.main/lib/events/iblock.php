<?php
/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 05.04.2019
 * Time: 16:12
 */

namespace Caweb\Main\Events;



use Caweb\Main\Log\Write;
use Caweb\Main\ORD;
use Caweb\Main\Tools;

class Iblock{
    public static $disableEvents = false;
    const FORMS_ID = array(12,13,14,15,16,17,18,19,32,33,37);
    const BANNER_IBLOCK_ID = 34;
    const MAIN_BANNERS_IBLOCK_ID = 35;
    const NEWS_SALES_IBLOCK_ID = 21;
    const CONTENT_IBLOCK_TYPE = 'aspro_stroy_content';
    const ADV_IBLOCK_TYPE= 'aspro_stroy_content';
    const PROPERTY_MARKER_ORD_ID = 358;
    const PROPERTY_MARKER_ORD_CODE = 'MARKER_ORD';
    const PROPERTY_BANNER_LINK_CODE = 'LINK';
    const PROPERTY_RELATED_BANNER_ELEMENT_CODE = 'RELATED_ELEMENT';
    public function sendBitrix24(&$arFields){
        $iblockId = (int)$arFields['IBLOCK_ID'];
        if (!in_array($iblockId, self::FORMS_ID)) return;
        if ($arFields['RESULT'] === false) return;
        $queryUrl = 'https://crm.strlog.ru/rest/52/ewopeeg6jjzaudzo/crm.lead.add.json';
        $properties = $arFields['PROPERTY_VALUES'];
        $fields = array(
            "TITLE" => Helper::getB24LeadTitle($iblockId),
            "ASSIGNED_BY_ID" => 4221,
            "SOURCE_ID" => 'WEB',
            "STATUS_ID" => "NEW",
            "OPENED" => "Y"
        );
        if (!empty($properties['NAME']))
            $fields['NAME'] = $properties['NAME'];

        if (!empty($properties['FIO']))
            $fields['NAME'] = $properties['FIO'];

        if (!empty($properties['PHONE']))
            $fields['PHONE'] = array(array("VALUE" => $properties['PHONE'], "VALUE_TYPE" => "WORK" ));

        if (!empty($properties['EMAIL']))
            $fields['EMAIL'] = array(array("VALUE" => $properties['EMAIL'], "VALUE_TYPE" => "WORK" ));

        if (!empty($properties['SERVICE']))
            $fields['UF_CRM_1565675706'] = $properties['SERVICE'];

        if (!empty($properties['PROJECT']))
            $fields['UF_CRM_1565675706'] = $properties['PROJECT'];

        if (!empty($properties['PRODUCT']))
            $fields['UF_CRM_1565675706'] = $properties['PRODUCT'];

        if (!empty($properties['NEED_PRODUCT']))
            $fields['UF_CRM_1565675706'] = $properties['NEED_PRODUCT'];

        if (!empty($properties['MESSAGE']['VALUE']['TEXT']))
            $fields['COMMENTS'] = $properties['MESSAGE']['VALUE']['TEXT'];

        $utm = Tools::getUtm();
        if (is_array($utm) && !empty($utm['UTM_SOURCE']))
            $fields = array_merge($fields, $utm);

        $queryData = http_build_query(
            array( 'fields' => $fields, 'params' => array("REGISTER_SONET_EVENT" => "Y") ));
        $curl = curl_init();
        curl_setopt_array($curl,
            array( CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_POST => 1, CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $queryUrl, CURLOPT_POSTFIELDS => $queryData));
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, 1);
        if (array_key_exists('error', $result)) Write::file('b24_error', $result['error_description']);
    }
    public function calculateBackground(&$arFields){
        if ((int)$arFields['IBLOCK_ID'] !== self::BANNER_IBLOCK_ID) return;
        if (empty($arFields['DETAIL_PICTURE']['name'])) return;
        $elementId = (int)$arFields['ID'];
        $dbElement = \CIBlockElement::GetByID($elementId)->Fetch();
        $bigPictPath = \Bitrix\Main\Application::getDocumentRoot().\CFile::GetPath((int)$dbElement['DETAIL_PICTURE']);
        $info = getimagesize($bigPictPath);
        switch ($info[2]) {
            case 1:
                $img = imageCreateFromGif($bigPictPath);
                break;
            case 2:
                $img = imageCreateFromJpeg($bigPictPath);
                break;
            case 3:
                $img = imageCreateFromPng($bigPictPath);
                break;
        }
        $total_r = $total_g = $total_b = 0;
        $c = ImageColorAt($img, 0, 0);
        $width = ImageSX($img);
        $height = ImageSY($img);
        $total_r += ($c>>16) & 0xFF;
        $total_g += ($c>>8) & 0xFF;
        $total_b += $c & 0xFF;
        $rgb = array(
            round($total_r),
            round($total_g),
            round($total_b)
        );
        $color = '#';
        foreach ($rgb as $row) {
            $color .= str_pad(dechex($row), 2, '0', STR_PAD_LEFT);
        }
        imageDestroy($img);
        \CIBlockElement::SetPropertyValuesEx($elementId, self::BANNER_IBLOCK_ID, array('BACKGROUND' => $color));
    }
    public function cancelElementAction(&$arFields){
        $iblockId = (int)$arFields['IBLOCK_ID'];
        if (empty($iblockId) && !empty((int)$arFields)){
            $iblockId = (int)\CIBlockElement::GetByID((int)$arFields)->Fetch()['IBLOCK_ID'];
        }
        if ($iblockId !== self::BANNER_IBLOCK_ID) return;

        $cnt = \CIBlockElement::GetList(
            array(),
            array('IBLOCK_ID' => self::BANNER_IBLOCK_ID),
            array(),
            false,
            array('ID', 'NAME')
        );
        echo $cnt;
        if ((int)$cnt === 0) return;
        global $APPLICATION;
        $APPLICATION->ThrowException('Не нада, пусть будет один элемент');
        return false;
    }
    public static function ordRelatedElements($fields){
        if (self::$disableEvents) return;
        $iblockId = (int)$fields['IBLOCK_ID'];
        switch ($iblockId){
            case self::MAIN_BANNERS_IBLOCK_ID:
            case self::NEWS_SALES_IBLOCK_ID:
                self::$disableEvents = true;
                ORD::elementUpdateAction($fields);
                break;
            default:
                return;
        }
        self::$disableEvents = false;
    }
}