<?
// подключаем пространство имен класса HighloadBlockTable и даём ему псевдоним HLBT для удобной работы
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

//подключаем модуль highloadblock
CModule::IncludeModule('highloadblock');

//Функция получения экземпляра класса:
function GetEntityDataClass($HlBlockId) {
	if (empty($HlBlockId) || $HlBlockId < 1)
	{
		return false;
	}
	$hlblock = HLBT::getById($HlBlockId)->fetch();
	$entity = HLBT::compileEntity($hlblock);
	$entity_data_class = $entity->getDataClass();
	return $entity_data_class;
}


if (((int)$arResult['IBLOCK_ID'] === 25) && (((int)$arResult['IBLOCK_SECTION_ID'] === 269) || ((int)$arResult['IBLOCK_SECTION_ID'] === 277)) ){
    $elementId = 43;
    if ((int)$arResult['IBLOCK_SECTION_ID'] === 277)
        $elementId = 6574;
    $ar = \CIBlockElement::GetByID($elementId)->GetNextElement();
    $fields = $ar->GetFields();
    $props = $ar->GetProperties(array(), array());
    if (empty($arResult['DETAIL_PICTURE']))
        $arResult['DETAIL_PICTURE'] = $fields['DETAIL_PICTURE'];
    if (empty($arResult['DETAIL_TEXT']))
        $arResult['DETAIL_TEXT'] = $fields['DETAIL_TEXT'];
    foreach ($arResult['PROPERTIES'] as $code => $item){
        if(!empty($item['VALUE'])) continue;
        $arResult['PROPERTIES'][$code] = $props[$code];
    }
}

if($arParams['DISPLAY_PICTURE'] != 'N'){
	if(is_array($arResult['DETAIL_PICTURE'])){
		$arResult['GALLERY'][] = array(
			'DETAIL' => CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array('width' => 1200, 'height' => 900), BX_RESIZE_IMAGE_EXACT, true),
			'PREVIEW' => CFile::ResizeImageGet($arResult['DETAIL_PICTURE'] , array('width' => 1200, 'height' => 900), BX_RESIZE_IMAGE_EXACT, true),
			'THUMB' => CFile::ResizeImageGet($arResult['DETAIL_PICTURE'] , array('width' => 75, 'height' => 75), BX_RESIZE_IMAGE_EXACT, true),
			'TITLE' => (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['TITLE']) ? $arResult['DETAIL_PICTURE']['TITLE'] : $arResult['NAME'])),
			'ALT' => (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['ALT']) ? $arResult['DETAIL_PICTURE']['ALT'] : $arResult['NAME'])),
		);
	}
	
	if(!empty($arResult['PROPERTIES']['PHOTOS']['VALUE'])){
		foreach($arResult['PROPERTIES']['PHOTOS']['VALUE'] as $img){
            $arPhoto = CFile::GetFileArray($img);
			$arResult['GALLERY'][] = array(
				'DETAIL' => CFile::ResizeImageGet($img, array('width' => 1200, 'height' => 900), BX_RESIZE_IMAGE_PROPORTIONAL, true),
				'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 1200, 'height' => 900), BX_RESIZE_IMAGE_EXACT, true),
				'THUMB' => CFile::ResizeImageGet($img , array('width' => 75, 'height' => 75), BX_RESIZE_IMAGE_EXACT, true),
				'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arResult['NAME'])),
				'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME'])),
			);
		}
	}
}

if(!empty($arResult['PROPERTIES']['BIG_PHOTOS']['VALUE'])){
	foreach($arResult['PROPERTIES']['BIG_PHOTOS']['VALUE'] as $img){
        $arPhoto = CFile::GetFileArray($img);
		$arResult['GALLERY_BIG'][(int)$arPhoto['ID']] = array(
			'DETAIL' => CFile::ResizeImageGet($img, array('width' => 1200, 'height' => 900), BX_RESIZE_IMAGE_PROPORTIONAL, true),
			'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 500, 'height' => 375), BX_RESIZE_IMAGE_EXACT, true),
			'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arResult['NAME'])),
			'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME'])),
		);
	}
	krsort($arResult['GALLERY_BIG']);
}

if(!empty($arResult['DISPLAY_PROPERTIES']['PLANIROVKA']['VALUE'])){
	foreach($arResult['DISPLAY_PROPERTIES']['PLANIROVKA']['VALUE'] as $img){
		$arResult['PLANIROVKA'][] = array(
			'DETAIL' => ($arPhoto = CFile::GetFileArray($img)),
			'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 634, 'height' => 476), BX_RESIZE_IMAGE_EXACT, true),
			'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arResult['NAME'])),
			'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME'])),
		);
	}
}

if($arResult['DISPLAY_PROPERTIES']){
	$arResult['CHARACTERISTICS'] = array();
	$arResult['VIDEO'] = array();
	foreach($arResult['DISPLAY_PROPERTIES'] as $PCODE => $arProp){
		if(!in_array($arProp['CODE'], array('PERIOD', 'PHOTOS', 'PRICE', 'PRICEOLD', 'ARTICLE', 'STATUS', 'DOCUMENTS', 'LINK_GOODS', 'LINK_STAFF', 'LINK_REVIEWS', 'LINK_PROJECTS', 'LINK_SERVICES', 'FORM_ORDER', 'FORM_QUESTION', 'PHOTOPOS', 'TIZERS', 'PLANIROVKA', 'STIKERS'))){
			if($arProp["VALUE"] || strlen($arProp["VALUE"])){
				if ($arProp['USER_TYPE'] == 'video') {
					if (count($arProp['PROPERTY_VALUE_ID']) > 1) {
						foreach($arProp['VALUE'] as $val){
							if($val['path']){
								$arResult['VIDEO'][] = $val;
							}
						}
					}
					elseif($arProp['VALUE']['path']){
						$arResult['VIDEO'][] = $arProp['VALUE'];
					}
				}
				else{
					$arResult['CHARACTERISTICS'][$PCODE] = $arProp;
				}
			}
		}
	}
}

//region Sales

use Bitrix\Main\Loader;
Loader::includeModule('highloadblock');
use Bitrix\Highloadblock;
use Bitrix\Main\Entity;

try {
    $hlBlock = Highloadblock\HighloadBlockTable::getRowById(3);
    $class = Highloadblock\HighloadBlockTable::compileEntity($hlBlock)->getDataClass();
    $db = $class::getList(array(
        'filter' => array(
            'LOGIC' => 'OR',
            array('UF_SECTIONS' => $arResult['IBLOCK_SECTION_ID']),
            array('UF_ELEMENTS' => $arResult['ID'])
        ),
        'select' => array(
            "NAME" => "UF_NAME",
            "PREVIEW_TEXT" => "UF_TEXT",
            "DETAIL_PAGE_URL" => "UF_LINK",
            "UF_PREFIX"
        )
    ));
    while($ar = $db->fetch()){
        $arResult['SALES'][] = $ar;
    }
} catch (\Bitrix\Main\ObjectPropertyException $e) {
} catch (\Bitrix\Main\ArgumentException $e) {
} catch (\Bitrix\Main\SystemException $e) {
}


$db = CIBlockElement::GetList(array(),array('IBLOCK_ID' => 21, 'ACTIVE' => 'Y','PROPERTY_LINK_GOODS.ID' => $arResult['ID']));
while ($ar = $db->GetNextElement()){
    $fields = $ar->GetFields();
    $fields['UF_PREFIX'] = 'Акция';
    $arResult['SALES'][] = $fields;
}

//endregion
if ($arResult['PROPERTIES']['DESC_RIGHT_SIDE']['VALUE']){
    $arResult['PROPERTIES']['DESC_RIGHT_SIDE']['FILE'] = \CFile::GetFileArray($arResult['PROPERTIES']['DESC_RIGHT_SIDE']['VALUE']);
    $arResult['PROPERTIES']['DESC_RIGHT_SIDE']['IS_IMAGE'] = \CFile::IsImage($arResult['PROPERTIES']['DESC_RIGHT_SIDE']['FILE']['FILE_NAME'], $arResult['PROPERTIES']['DESC_RIGHT_SIDE']['FILE']['CONTENT_TYPE']);
}
if (empty($arResult['PROPERTIES']['CREDIT']['VALUE'])){
    $arResult['PROPERTIES']['CREDIT']['VALUE'] = "/info/articles/vozmozhnye-sposoby-oplaty/";
}
if (empty($arResult['PROPERTIES']['CREDIT']['DESCRIPTION'])){
    $arResult['PROPERTIES']['CREDIT']['DESCRIPTION'] = "Рассрочка 0-0-12";
}
?>