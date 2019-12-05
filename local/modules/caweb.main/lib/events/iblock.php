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
    const FORMS_ID = array(16, 17, 14, 12);
    public function sendBitrix24(&$arFields){
        $iblockId = (int)$arFields['IBLOCK_ID'];
        if (!in_array($iblockId, self::FORMS_ID)) return;
        if ($arFields['RESULT'] === false) return;
        $queryUrl = 'https://crm.strlog.ru/rest/1/73yn8j1f0h4qb08d/crm.lead.add/';
        $property = $arFields['PROPERTY_VALUES'];
        $name = $property['NAME'];
        $service = $property['NEED_PRODUCT'];
        if ($iblockId === 17) $name = $property['FIO'];
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
}