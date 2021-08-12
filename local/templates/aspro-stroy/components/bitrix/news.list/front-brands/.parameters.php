<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

\Bitrix\Main\Loader::includeModule('iblock');
/* show sort property */
$arPropertySort = $arPropertySortShow = $arPropertySortDefault = $arPropertyDefaultSort = $arProperty_LNS = array();
$arPropertySortDefault = array('name', 'sort');
$arPropertySort = array('name' => GetMessage('V_NAME'), 'sort' => GetMessage('V_SORT'));
$rsProp = CIBlockProperty::GetList(array('sort' => 'asc', 'name' => 'asc'), Array('ACTIVE' => 'Y', 'IBLOCK_ID' => (isset($arCurrentValues['IBLOCK_ID']) ? $arCurrentValues['IBLOCK_ID'] : $arCurrentValues['ID'])));

while($arr = $rsProp->Fetch()){
	$arPropertySort[$arr['CODE']] = $arr['NAME'];
	$strPropName = '['.$arr['ID'].']'.('' != $arr['CODE'] ? '['.$arr['CODE'].']' : '').' '.$arr['NAME'];
	if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S")))
	{
		$arProperty_LNS[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
	if ('S' == $arr['PROPERTY_TYPE'] && 'directory' == $arr['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arr))
		$arHighloadPropList[$arr['CODE']] = $strPropName;
}

if($arCurrentValues['SORT_PROP']){
	foreach($arCurrentValues['SORT_PROP'] as $code){
		$arPropertyDefaultSort[$code] = $arPropertySort[$code];
	}
}
else{
	foreach($arPropertySortDefault as $code){
		$arPropertyDefaultSort[$code] = $arPropertySort[$code];
	}
}

$arTemplateParameters = array(
	'SHOW_DETAIL_LINK' => array(
		'NAME' => GetMessage('SHOW_DETAIL_LINK'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SHOW_SECTIONS' => array(
		'SORT' => 100,
		'NAME' => GetMessage('SHOW_SECTIONS'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SHOW_GOODS' => array(
		'SORT' => 100,
		'NAME' => GetMessage('SHOW_GOODS'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'S_DETAIL_PRODUCT' => array(
		'SORT' => 701,
		'NAME' => GetMessage('S_DETAIL_PRODUCT'),
		'TYPE' => 'TEXT',
		'DEFAULT' => GetMessage('S_DETAIL_PRODUCT_TEXT'),
	),
	'SHOW_SLIDE_PROP' => array(
		'NAME' => GetMessage('T_SHOW_SLIDE_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => $arProperty_LNS,
		'SIZE' => 3,
		'MULTIPLE' => 'Y',
		"ADDITIONAL_VALUES" => "Y"
	),
);
?>