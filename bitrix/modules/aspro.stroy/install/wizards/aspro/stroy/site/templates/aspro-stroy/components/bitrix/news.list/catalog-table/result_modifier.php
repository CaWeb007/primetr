<?
// get section names elements
foreach($arResult['ITEMS'] as $arItem){
	$arSectionsIDs[] = $arItem['IBLOCK_SECTION_ID'];
}
if($arSectionsIDs){
	$arSectionsIDs = array_unique($arSectionsIDs);
	$arSectionsTmp = CStroy::cacheSection(false, array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arSectionsIDs), false, array('ID', 'NAME'));
	foreach($arSectionsTmp as $arSection){
		$arSections[$arSection['ID']] = $arSection;
	}
}
$arResult['CHARACTERISTICS'] = array();
if(is_array($arResult['ITEMS'])){
	foreach($arResult['ITEMS'] as $i => $arItem){
		if($arItem['PROPERTIES']){
			foreach($arItem['PROPERTIES'] as $PCODE => $arProp){
				if(in_array($arProp['CODE'], $arParams["SHOW_SLIDE_PROP"])){
					if($arProp["VALUE"] || strlen($arProp["VALUE"])){
						$arItem['CHARACTERISTICS'][$PCODE] = $arProp;						
					}
				}
			}
		}
		$arItem['SECTION_NAME'] = $arSections[$arItem['IBLOCK_SECTION_ID']]['NAME'];
		$arResult['ITEMS'][$i] = $arItem;
	}
}

?>