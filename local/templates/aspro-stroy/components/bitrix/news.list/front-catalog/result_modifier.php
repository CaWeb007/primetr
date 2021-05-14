<?
// get goods with property SHOW_ON_INDEX_PAGE == Y
if($arResult['ITEMS']){
	foreach($arResult['ITEMS'] as $i => $arItem){
		if($arItem['PROPERTIES']){
			foreach($arItem['PROPERTIES'] as $PCODE => $arProp){
				/*if(!in_array($arProp['CODE'], array('PHOTOS', 'PRICE', 'PRICEOLD', /*'ARTICLE', 'STATUS',// 'DOCUMENTS', 'LINK_GOODS', 'LINK_STAFF', 'LINK_REVIEWS', 'LINK_PROJECTS', 'LINK_SERVICES', 'FORM_ORDER', 'FORM_QUESTION', 'PHOTOPOS', 'TIZERS', 'PLANIROVKA', 'STIKERS', 'SIZE', 'TYPE_BUILDINGS', 'SHOW_ON_INDEX_PAGE', 'FILTER_PRICE'))){*/
				if(in_array($arProp['CODE'], $arParams["SHOW_SLIDE_PROP"])){
					if($arProp["VALUE"] || strlen($arProp["VALUE"])){
						$arResult['ITEMS'][$i]['CHARACTERISTICS'][$PCODE] = $arProp;						
					}
				}
			}
		}
		if($arItem['PROPERTIES']['SHOW_ON_INDEX_PAGE']['VALUE_XML_ID'] !== 'YES'){
			unset($arResult['ITEMS'][$i]);
			unset($arResult['ELEMENTS'][$i]);
		}
		else{
			$arGoodsSectionsIDs[] = $arItem["IBLOCK_SECTION_ID"];
		}
	}
	
	// get good`s section name
	if($arGoodsSectionsIDs){
		$arGoodsSectionsIDs = array_unique($arGoodsSectionsIDs);
		$arGoodsSections = CCache::CIBLockSection_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N', 'RESULT' => array('NAME'))), array('ID' => $arGoodSectionsIDs), false, array('ID', 'NAME'));
		if($arGoodsSections){
			foreach($arResult['ITEMS'] as $i => $arItem){
				$arResult['ITEMS'][$i]['SECTION_NAME'] = $arGoodsSections[$arItem["IBLOCK_SECTION_ID"]];
			}
		}
	}
}
?>