<?

if(is_array($arResult['FIELDS']['DETAIL_PICTURE'])){
	$arResult['GALLERY'][] = array(
		'DETAIL' => $arResult['DETAIL_PICTURE'],
		'PREVIEW' => CFile::ResizeImageGet($arResult['DETAIL_PICTURE'] , array('width' => 823, 'height' => 475), BX_RESIZE_IMAGE_EXACT, true),
		// 'THUMB' => CFile::ResizeImageGet($arResult['DETAIL_PICTURE'] , array('width' => 98, 'height' => 75), BX_RESIZE_IMAGE_EXACT, true),
		'TITLE' => (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['TITLE']) ? $arResult['DETAIL_PICTURE']['TITLE'] : $arResult['NAME'])),
		'ALT' => (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['ALT']) ? $arResult['DETAIL_PICTURE']['ALT'] : $arResult['NAME'])),
	);
}

if(!empty($arResult['PROPERTIES']['PHOTOS']['VALUE'])){
	foreach($arResult['PROPERTIES']['PHOTOS']['VALUE'] as $img){
		$arResult['GALLERY'][] = array(
			'DETAIL' => ($arPhoto = CFile::GetFileArray($img)),
			'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 823, 'height' => 475), BX_RESIZE_IMAGE_EXACT, true),
			// 'THUMB' => CFile::ResizeImageGet($img , array('width' => 98, 'height' => 75), BX_RESIZE_IMAGE_EXACT, true),
			'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arResult['NAME'])),
			'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME'])),
		);
	}
}
if(!empty($arResult['PROPERTIES']['BIG_PHOTOS']['VALUE'])){
	foreach($arResult['PROPERTIES']['BIG_PHOTOS']['VALUE'] as $img){
		$arResult['GALLERY_BIG'][] = array(
			'DETAIL' => ($arPhoto = CFile::GetFileArray($img)),
			'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 800, 'height' => 500), BX_RESIZE_PROPORTIONAL, true),
			'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arResult['NAME'])),
			'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME'])),
		);
	}
}

if($arResult['DISPLAY_PROPERTIES']['LINK_REVIEWS']['VALUE']){
	$arResult["REVIEWS"] = CCache::CIBlockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag(CCache::$arIBlocks[SITE_ID]['aspro_stroy_content']['aspro_stroy_reviews'][0]), 'MULTI' => 'Y')), array('ID' => $arResult['DISPLAY_PROPERTIES']['LINK_REVIEWS']['VALUE'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, false, array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_POST', 'PROPERTY_DOCUMENTS', 'PREVIEW_TEXT'));
}

if($arResult['DISPLAY_PROPERTIES']){
	$arResult['CHARACTERISTICS'] = array();
	$arResult['VIDEO'] = array();
	foreach($arResult['DISPLAY_PROPERTIES'] as $PCODE => $arProp){
		if(!in_array($arProp['CODE'], array('DOCUMENTS', 'LINK_PROJECTS', 'FORM_QUESTION', 'FORM_PROJECT', 'LINK_REVIEWS'))){
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
?>