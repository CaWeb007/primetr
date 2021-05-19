<?
\Bitrix\Main\Loader::includeModule('caweb.main');
$arResult['SECTIONS'] = array();
$arResult['SECTIONS'] = \Caweb\Main\Iblock\FrontComponent::getInstance()->getItems();