<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$aMenuLinksExt = array();

if($arMenuParametrs = CStroy::GetDirMenuParametrs(__DIR__)){
	if($arMenuParametrs['MENU_SHOW_SECTIONS'] == 'Y'){
		$arSections = CCache::CIBlockSection_GetList(
		    array(
		        'SORT' => 'ASC',
                'ID' => 'ASC',
                'CACHE' => array(
                    'TAG' => CCache::GetIBlockCacheTag(CCache::$arIBlocks[SITE_ID]['aspro_stroy_catalog']['aspro_stroy_catalog'][0]),
                    'MULTI' => 'Y'
                )
            ),
            array(
                'IBLOCK_ID' => CCache::$arIBlocks[SITE_ID]['aspro_stroy_catalog']['aspro_stroy_catalog'][0],
                'ACTIVE' => 'Y',
                'GLOBAL_ACTIVE' => 'Y',
                'ACTIVE_DATE' => 'Y'
            ),
            false,
            array('IBLOCK_ID','ID','NAME', 'SECTION_PAGE_URL','DEPTH_LEVEL', 'UF_COLOR', 'UF_FW_ICON_CLASS')
        );
		$arSectionsByParentSectionID = CCache::GroupArrayBy($arSections, array('MULTI' => 'Y', 'GROUP' => array('IBLOCK_SECTION_ID')));
	}
    $arSectionsUnset = array();
	foreach ($arSections as $item){
	    if ($item['DEPTH_LEVEL'] < 3) continue;
	    $arSectionsUnset[] = (int)$item['ID'];
    }
	if($arMenuParametrs['MENU_SHOW_ELEMENTS'] == 'Y'){
		$arItems = CCache::CIBlockElement_GetList(array('SORT' => 'ASC', 'ID' => 'DESC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag(CCache::$arIBlocks[SITE_ID]['aspro_stroy_catalog']['aspro_stroy_catalog'][0]), 'MULTI' => 'Y')), array('IBLOCK_ID' => CCache::$arIBlocks[SITE_ID]['aspro_stroy_catalog']['aspro_stroy_catalog'][0], 'ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'INCLUDE_SUBSECTIONS' => 'Y'));
		if($arMenuParametrs['MENU_SHOW_SECTIONS'] == 'Y'){
			$arItemsBySectionID = CCache::GroupArrayBy($arItems, array('MULTI' => 'Y', 'GROUP' => array('IBLOCK_SECTION_ID')));
		}
		else{
			$arItemsRoot = CCache::CIBlockElement_GetList(array('SORT' => 'ASC', 'ID' => 'DESC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag(CCache::$arIBlocks[SITE_ID]['aspro_stroy_catalog']['aspro_stroy_catalog'][0]), 'MULTI' => 'Y')), array('IBLOCK_ID' => CCache::$arIBlocks[SITE_ID]['aspro_stroy_catalog']['aspro_stroy_catalog'][0], 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', 'SECTION_ID' => 0));
			$arItems = array_merge((array)$arItems, (array)$arItemsRoot);
		}
	}
    $arItemsBySectionID = array_diff_key($arItemsBySectionID, array_flip($arSectionsUnset));

	if($arSections){
		CStroy::getSectionChilds(false, $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt);
	}

	if($arItems && $arMenuParametrs['MENU_SHOW_SECTIONS'] != 'Y'){
		foreach($arItems as $arItem){
			$aMenuLinksExt[] = array($arItem['NAME'], $arItem['DETAIL_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => 1));
		}
	}
}

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>