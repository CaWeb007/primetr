<?
foreach($arResult['ITEMS'] as $arItem){
	if($SID = $arItem['IBLOCK_SECTION_ID']){
		$arSectionsIDs[] = $SID;
	}
}

if($arSectionsIDs){
	$arResult['SECTIONS'] = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs));
}

// group elements by sections
foreach($arResult['ITEMS'] as $arItem){
	$SID = ($arItem['IBLOCK_SECTION_ID'] ? $arItem['IBLOCK_SECTION_ID'] : 0);
	$arResult['SECTIONS'][$SID]['ITEMS'][$arItem['ID']] = $arItem;

	/*gallery block*/
	if(!empty($arItem['PROPERTIES']['MORE_PHOTOS']['VALUE'])){
		foreach($arItem['PROPERTIES']['MORE_PHOTOS']['VALUE'] as $img){
			$arResult['SECTIONS'][$SID]['ITEMS'][$arItem['ID']]['GALLERY'][] = array(
				'DETAIL' => ($arPhoto = CFile::GetFileArray($img)),
				// 'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 800, 'height' => 500), BX_RESIZE_PROPORTIONAL, true),
				'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arItem['NAME'])),
				'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arItem['NAME'])),
			);
		}
	}
	if($arItem['DISPLAY_PROPERTIES']){
		foreach($arItem['DISPLAY_PROPERTIES'] as $PCODE => $arProperty){
			if($PCODE=="MORE_PHOTOS")
				unset($arResult['SECTIONS'][$SID]['ITEMS'][$arItem['ID']]['DISPLAY_PROPERTIES'][$PCODE]);
		}
	}
}

// unset empty sections
if(is_array($arResult['SECTIONS'])){
	foreach($arResult['SECTIONS'] as $i => $arSection){
		if(!$arSection['ITEMS']){
			unset($arResult['SECTIONS'][$i]);
		}
	}
}


?>