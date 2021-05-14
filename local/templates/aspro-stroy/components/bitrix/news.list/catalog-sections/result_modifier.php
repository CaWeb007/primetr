<?
// get all subsections of PARENT_SECTION or root sections
$arSectionsFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
if($arParams['PARENT_SECTION']){
	$arSectionsFilter = array_merge($arSectionsFilter, array('SECTION_ID' => $arParams['PARENT_SECTION'], '>DEPTH_LEVEL' => '1'));
}
else{
	$arSectionsFilter['DEPTH_LEVEL'] = '1';
}
$arResult['SECTIONS'] = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), $arSectionsFilter, false, array('ID', 'NAME', 'IBLOCK_ID', 'DEPTH_LEVEL', 'SECTION_PAGE_URL', 'PICTURE', 'DETAIL_PICTURE', 'UF_INFOTEXT', 'DESCRIPTION'));

unset($arResult['ITEMS']);
?>