<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
$this->setFrameMode(true);
if($_arResult = CStroy::CheckSmartFilterSEF($arParams, $component)){
	$arResult = $_arResult;
	include  __DIR__.'/section.php';
	return;
}

global $APPLICATION, $arTheme;
$APPLICATION->SetPageProperty('MENU', 'N');

// get element
$arItemFilter = CStroy::GetCurrentElementFilter($arResult['VARIABLES'], $arParams);
$arElement = CCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), $arItemFilter, false, false, array('ID', 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'PROPERTY_LINK_PROJECTS'));

// sort start
$sort_default = $arParams['SORT_PROP_DEFAULT'] ? $arParams['SORT_PROP_DEFAULT'] : 'name';
$order_default = $arParams['SORT_DIRECTION'] ? $arParams['SORT_DIRECTION'] : 'asc';
$arPropertySortDefault = array('name', 'sort');

$sort = !empty($_COOKIE['catalogSort']) ? $_COOKIE['catalogSort'] : $sort_default;
$order = !empty($_COOKIE['catalogOrder']) ? $_COOKIE['catalogOrder'] : $order_default;
$arAvailableSort = array(
	'name' => array(
		'SORT' => 'NAME',
		'ORDER_VALUES' => array(
			'asc' => GetMessage('sort_title').GetMessage('sort_name_asc'),
			'desc' => GetMessage('sort_title').GetMessage('sort_name_desc'),
		),
	),
	'sort' => array(
		'SORT' => 'SORT',
		'ORDER_VALUES' => array(
			$order_default => GetMessage('sort_title').GetMessage('sort_sort'),
		)
	),
);

foreach($arAvailableSort as $prop => $arProp){
	if(!in_array($prop, $arParams['SORT_PROP']) && $sort_default !== $prop){
		unset($arAvailableSort[$prop]);
	}
}

if($arParams['SORT_PROP']){
	if(!isset($_SESSION[$arParams['IBLOCK_ID'].md5(serialize((array)$arParams['SORT_PROP']))])){
		foreach($arParams['SORT_PROP'] as $prop){
			if(!isset($arAvailableSort[$prop])){
				$dbRes = CIBlockProperty::GetList(array(), array('ACTIVE' => 'Y', 'IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $prop));
				while($arPropperty = $dbRes->Fetch()){
					$arAvailableSort[$prop] = array(
						'SORT' => 'PROPERTY_'.$prop,
						'ORDER_VALUES' => array(),
					);

					if($prop == 'PRICE' || $prop == 'FILTER_PRICE'){
						$arAvailableSort[$prop]['ORDER_VALUES']['asc'] = GetMessage('sort_title').GetMessage('sort_PRICE_asc');
						$arAvailableSort[$prop]['ORDER_VALUES']['desc'] = GetMessage('sort_title').GetMessage('sort_PRICE_desc');
					}
					else{
						$arAvailableSort[$prop]['ORDER_VALUES']['asc'] = GetMessage('sort_title_property', array('#CODE#' => $arPropperty['NAME'])).GetMessage('sort_title_property_asc');
						$arAvailableSort[$prop]['ORDER_VALUES']['desc'] = GetMessage('sort_title_property', array('#CODE#' => $arPropperty['NAME'])).GetMessage('sort_title_property_desc');
					}
				}
			}
		}
		$_SESSION[$arParams['IBLOCK_ID'].md5(serialize((array)$arParams['SORT_PROP']))] = $arAvailableSort;
	}
	else{
		$arAvailableSort = $_SESSION[$arParams['IBLOCK_ID'].md5(serialize((array)$arParams['SORT_PROP']))];
	}
}

$sort1 = $arParams["SORT_BY1"];
$order1 = $arParams["SORT_ORDER1"];
if($arAvailableSort[$sort]["SORT"])
	$sort1 = $arAvailableSort[$sort]["SORT"];
if($order)
	$order1 = strtoupper($order);

// sort end

$arSort=array($sort1 => $order1, $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]);
$arElementNext = array();
$arAllElements = CCache::CIblockElement_GetList(array($arSort, 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => $arElement["IBLOCK_SECTION_ID"]/*, ">ID" => $arElement["ID"]*/ ), false, false, array('ID', 'DETAIL_PAGE_URL', 'IBLOCK_ID'));
if($arAllElements)
{
	$url_page = $APPLICATION->GetCurPage();
	$key_item = 0;
	foreach($arAllElements as $key => $arItemElement)
	{
		if($arItemElement["DETAIL_PAGE_URL"] == $url_page)
		{
			$key_item = $key;
			break;
		}
	}
	if(strlen($key_item))
	{
		$arElementNext = $arAllElements[$key_item+1];
	}
	if($arElementNext)
	{
		if($arElementNext["DETAIL_PAGE_URL"] && is_array($arElementNext["DETAIL_PAGE_URL"])){
			$arElementNext["DETAIL_PAGE_URL"]=current($arElementNext["DETAIL_PAGE_URL"]);
		}
	}
}
$arSection = CCache::CIBlockSection_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arElement['IBLOCK_ID']), 'MULTI' => 'N')), CStroy::GetCurrentSectionFilter($arResult['VARIABLES'], $arParams), false, array('ID', 'NAME', 'SECTION_PAGE_URL'));
$url=($arSection["SECTION_PAGE_URL"] ? $arSection["SECTION_PAGE_URL"] : $arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']);

?>
<?if(!$arElement && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("ELEMENT_NOTFOUND")?></div>
<?elseif(!$arElement && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CStroy::goto404Page();?>
<?else:?>
	<?CStroy::AddMeta(
		array(
			'og:description' => $arElement['PREVIEW_TEXT'],
			'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
		)
	);?>
	
	<?if ($APPLICATION->GetShowIncludeAreas()){?>
		<div class="edit_area_block"></div></div></div></div></div></div></div>
	<?}else{?>
	    </div></div></div>
	<?}?>
	<div class="catalog detail" itemscope itemtype="http://schema.org/Product">
		<?$APPLICATION->IncludeComponent(
			"bitrix:news.detail",
			"catalog",
			Array(
				"S_ASK_QUESTION" => $arParams["S_ASK_QUESTION"],
				"S_ORDER_PRODUCT" => $arParams["S_ORDER_PRODUCT"],
				"T_GALLERY" => $arParams["T_GALLERY"],
				"T_DOCS" => $arParams["T_DOCS"],
				"T_PROJECTS" => $arParams["T_PROJECTS"],
				"T_CHARACTERISTICS" => $arParams["T_CHARACTERISTICS"],
				"T_VIDEO" => $arParams["T_VIDEO"],
				"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
				"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
				"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
				"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
				"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
				"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
				"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"META_KEYWORDS" => $arParams["META_KEYWORDS"],
				"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
				"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
				"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
				"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
				"SET_TITLE" => $arParams["SET_TITLE"],
				"SET_STATUS_404" => $arParams["SET_STATUS_404"],
				"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
				"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
				"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
				"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
				"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
				"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
				"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
				"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
				"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
				"CHECK_DATES" => $arParams["CHECK_DATES"],
				"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
				"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
				"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
				"USE_SHARE" 			=> $arParams["USE_SHARE"],
				"SHARE_HIDE" 			=> $arParams["SHARE_HIDE"],
				"SHARE_TEMPLATE" 		=> $arParams["SHARE_TEMPLATE"],
				"SHARE_HANDLERS" 		=> $arParams["SHARE_HANDLERS"],
				"SHARE_SHORTEN_URL_LOGIN"	=> $arParams["SHARE_SHORTEN_URL_LOGIN"],
				"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
				"BRAND_PROP_CODE" => $arParams["DETAIL_BRAND_PROP_CODE"],
				"BRAND_USE" => $arParams["DETAIL_BRAND_USE"],
			),
			$component
		);?>
	</div>
	<div class="row nomargin under_content">
		<div class="maxwidth-theme">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div style="clear:both"></div>
						<a class="back-url pull-left" href="<?=$url;?>"><i class="fa fa-chevron-left"></i><?=GetMessage('BACK_LINK')?></a>
						<?if($arElementNext["ID"]){?>
							<a class="back-url next pull-right" href="<?=$arElementNext["DETAIL_PAGE_URL"];?>"><?=GetMessage('NEXT_LINK')?><i class="fa fa-chevron-right"></i></a>
						<?}?>
						<div style="clear:both"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
		<?// projects links?>
		<?if(in_array('LINK_PROJECTS', $arParams['DETAIL_PROPERTY_CODE']) && $arElement['PROPERTY_LINK_PROJECTS_VALUE']):?>

			<?if ($APPLICATION->GetShowIncludeAreas()){?>
				</div></div></div></div></div>
				<div><div><div><div>
			<?}else{?>
			    </div></div>
			<?}?>
		<?endif;?>

	<?
	if(is_array($arElement['IBLOCK_SECTION_ID']) && count($arElement['IBLOCK_SECTION_ID']) > 1){
		CStroy::CheckAdditionalChainInMultiLevel($arResult, $arParams, $arElement);
	}
	?>
	<script type="text/javascript">
		$('.container .maxwidth-theme .col-md-3.left-menu-md').remove();
		$('.container .maxwidth-theme .content-md').removeClass('col-md-9');
		$('.container .maxwidth-theme .content-md').removeClass('col-sm-9');
		$('.container .maxwidth-theme .content-md').removeClass('col-xs-8');
		$('.container .maxwidth-theme .content-md').addClass('col-md-12');
		$('.body').addClass('detail_page');
	</script>
<?endif;?>