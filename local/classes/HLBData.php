<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 10.01.2019
 * Time: 7:49
 */
use Bitrix\Main\Loader;
Loader::includeModule("highloadblock");

use Bitrix\Highloadblock as HL;
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

CModule::IncludeModule('highloadblock');



class HLBData
{
  static function GetEntityDataClass($HlBlockId) {
    if (empty($HlBlockId) || $HlBlockId < 1)
    {
      return false;
    }
    $hlblock = HLBT::getById($HlBlockId)->fetch();
    $entity = HLBT::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    return $entity_data_class;
  }

  static function addHLBData ($hlbId, $arData) {
    $entity_data_class = self::GetEntityDataClass($hlbId);
    $result = $entity_data_class::add($arData);
    return  $result;
  }
  static function deleteHLBData ($hlbId, $ID) {
    $entity_data_class = self::GetEntityDataClass($hlbId);
    $result = $entity_data_class::delete($ID);
    return  $result;
  }

  static function getHLBData ($hlbId, $arData) {
    $entity_data_class = self::GetEntityDataClass($hlbId);
    $userTempData = $entity_data_class::getList($arData);
    $result = '';
    while($elTemp = $userTempData->fetch()) {
      $result = $elTemp;
    }
    return $result;
  }

  static function getHLBDatas ($hlbId, $arData) {
    $entity_data_class = self::GetEntityDataClass($hlbId);
    $userTempData = $entity_data_class::getList($arData);
    $result = array();
    while($elTemp = $userTempData->fetch()) {
      $result[] = $elTemp;
    }
    return $result;
  }

  static function updateHLBData ($hlbId, $entityId, $arData) {
    $entity_data_class = self::GetEntityDataClass($hlbId);
    $result = $entity_data_class::update($entityId, $arData);
  }

}
