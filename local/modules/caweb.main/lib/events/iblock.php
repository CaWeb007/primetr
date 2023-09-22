<?php
/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 05.04.2019
 * Time: 16:12
 */

namespace Caweb\Main\Events;



use Caweb\Main\Log\Write;

class Iblock{
    const FORMS_ID = array(16, 17, 14, 12, 33, 37);
    const BANNER_IBLOCK_ID = 35;
    const MAIN_BANNERS_IBLOCK_ID = 35;
    const CONTENT_IBLOCK_TYPE = 'aspro_stroy_content';
    const ADV_IBLOCK_TYPE= 'aspro_stroy_content';
    const PROPERTY_MARKER_ORD_ID = 358;
    public function sendBitrix24(&$arFields){
        $iblockId = (int)$arFields['IBLOCK_ID'];
        if (!in_array($iblockId, self::FORMS_ID)) return;
        if ($arFields['RESULT'] === false) return;
        $queryUrl = 'https://crm.strlog.ru/rest/2223/34k6ig5n6gg4rd9g/crm.lead.add/';
        $property = $arFields['PROPERTY_VALUES'];
        $name = $property['NAME'];
        $service = $property['NEED_PRODUCT'];
        if ($iblockId === 17) $name = $property['FIO'];
        if ($iblockId === 33) $name = $property['FIO'];
        if ($iblockId === 14) $service = $property['SERVICE'];
        if ($iblockId === 12) $service = $property['PROJECT'];
        $queryData = http_build_query(
            array( 'fields' => array(
                "TITLE" => $arFields['NAME'],
                "NAME" => $name,
                "STATUS_ID" => "NEW",
                "OPENED" => "Y",
                "SOURCE_ID" => "WEB",
                "ASSIGNED_BY_ID" => 1,
                "PHONE" => array(array("VALUE" => $property['PHONE'], "VALUE_TYPE" => "WORK" )),
                "EMAIL" => array(array("VALUE" => $property['EMAIL'], "VALUE_TYPE" => "WORK" )),
                "UF_CRM_1565675706" => $service,
                "COMMENTS" => $property['MESSAGE']['VALUE']['TEXT']
                ), 'params' => array("REGISTER_SONET_EVENT" => "Y") ));
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
}