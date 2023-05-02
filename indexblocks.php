<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?ob_start();?>
	<div class="greyline">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."include/front-sliders.php",
                "EDIT_TEMPLATE" => "standard.php"
            )
        );?>
		<div class="row">
			<div class="maxwidth-theme">
				<div class="col-md-12">
					<?$APPLICATION->IncludeComponent(
						"bitrix:news.list", 
						"front-banners-small", 
						array(
							"IBLOCK_TYPE" => "aspro_stroy_content",
							"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_stroy_content"]["aspro_stroy_advtsmall"][0],
							"NEWS_COUNT" => "5",
							"SORT_BY1" => "SORT",
							"SORT_ORDER1" => "ASC",
							"SORT_BY2" => "ID",
							"SORT_ORDER2" => "ASC",
							"FILTER_NAME" => "",
							"FIELD_CODE" => array(
								0 => "NAME",
								1 => "",
							),
							"PROPERTY_CODE" => array(
								0 => "",
								1 => "LINK",
								2 => "ICON",
								3 => "",
							),
							"CHECK_DATES" => "Y",
							"DETAIL_URL" => "",
							"AJAX_MODE" => "N",
							"AJAX_OPTION_JUMP" => "N",
							"AJAX_OPTION_STYLE" => "Y",
							"AJAX_OPTION_HISTORY" => "N",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "3600000",
							"CACHE_FILTER" => "Y",
							"CACHE_GROUPS" => "N",
							"PREVIEW_TRUNCATE_LEN" => "",
							"ACTIVE_DATE_FORMAT" => "d.m.Y",
							"SET_TITLE" => "N",
							"SET_STATUS_404" => "N",
							"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
							"ADD_SECTIONS_CHAIN" => "N",
							"HIDE_LINK_WHEN_NO_DETAIL" => "N",
							"PARENT_SECTION" => "",
							"PARENT_SECTION_CODE" => "",
							"INCLUDE_SUBSECTIONS" => "N",
							"PAGER_TEMPLATE" => ".default",
							"DISPLAY_TOP_PAGER" => "N",
							"DISPLAY_BOTTOM_PAGER" => "N",
							"PAGER_TITLE" => "",
							"PAGER_SHOW_ALWAYS" => "N",
							"PAGER_DESC_NUMBERING" => "N",
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
							"PAGER_SHOW_ALL" => "N",
							"AJAX_OPTION_ADDITIONAL" => "",
							"SET_BROWSER_TITLE" => "N",
							"SET_META_KEYWORDS" => "N",
							"SET_META_DESCRIPTION" => "N",
							"COMPONENT_TEMPLATE" => "front-banners-small",
							"SET_LAST_MODIFIED" => "N",
							"PAGER_BASE_LINK_ENABLE" => "N",
							"SHOW_404" => "N",
							"MESSAGE_404" => ""
						),
						false
					);?>
				</div>
			</div>
		</div><!--/row-->
	</div>	
	<?if($bCatalogFilterIndex):?>	
		<div class="row">
			<div class="maxwidth-theme">
				<div class="col-md-12">
					<?$APPLICATION->IncludeComponent("bitrix:catalog.smart.filter", "front", array(
						"IBLOCK_TYPE" => "aspro_stroy_catalog",
							"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_stroy_catalog"]["aspro_stroy_catalog"][0],
							"SECTION_ID" => "",
							"FILTER_NAME" => "STROY_SMART_FILTER",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "3600",
							"CACHE_NOTES" => "",
							"CACHE_GROUPS" => "N",
							"SAVE_IN_SESSION" => "N",
							"FORM_URL" => SITE_DIR."filter_search/",
							"XML_EXPORT" => "N",
							"SECTION_TITLE" => "NAME",
							"SECTION_DESCRIPTION" => "DESCRIPTION",
							"INSTANT_RELOAD" => "Y",
							"DISPLAY_ELEMENT_COUNT" => "Y",
							"HIDE_NOT_AVAILABLE" => "N",
							"VIEW_MODE" => "horizontal",
							"SEF_RULE" => "/filter_search/filter/#SMART_FILTER_PATH#/apply/",
							"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
							"COMPONENT_TEMPLATE" => "front",
							"SEF_MODE" => "N",
							"SECTION_CODE" => "",
							"SECTION_CODE_PATH" => "",
							"PAGER_PARAMS_NAME" => "arrPager",
							"PROP_1" => "FILTER_PRICE",
							"PROP_2" => "SIZE",
							"PROP_3" => "TYPE_BUILDINGS"
						),
						false,
						array(
						"ACTIVE_COMPONENT" => "N"
						)
					);
					?>
				</div>
			</div>
		</div>
	<?endif;?>
	<?if($bCatalogIndex):?>		
		<div class="row">
			<div class="maxwidth-theme">
				<div class="col-md-12">
					<?$APPLICATION->IncludeComponent(
					"bitrix:news.list", 
					"front-catalog-elements-sections",
					array(
						"IBLOCK_TYPE" => "aspro_stroy_catalog",
						"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_stroy_catalog"]["aspro_stroy_catalog"][0],
						"NEWS_COUNT" => "8",
						"SORT_BY1" => "SORT",
						"SORT_ORDER1" => "ASC",
						"SORT_BY2" => "ID",
						"SORT_ORDER2" => "ASC",
						"FILTER_NAME" => "arCatalogItemsFilter1",
						"FIELD_CODE" => array(
							0 => "NAME",
							1 => "PREVIEW_PICTURE",
							2 => "DETAIL_PICTURE",
							3 => "",
						),
						"PROPERTY_CODE" => array(
							0 => "",
							1 => "SHOW_ON_INDEX_PAGE",
							2 => "STATUS",
							3 => "PRICE",
							4 => "PRICEOLD",
							5 => "ARTICLE",
							6 => "",
						),
						"CHECK_DATES" => "Y",
						"DETAIL_URL" => "",
						"AJAX_MODE" => "N",
						"AJAX_OPTION_JUMP" => "N",
						"AJAX_OPTION_STYLE" => "Y",
						"AJAX_OPTION_HISTORY" => "N",
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "3600000",
						"CACHE_FILTER" => "Y",
						"CACHE_GROUPS" => "N",
						"PREVIEW_TRUNCATE_LEN" => "",
						"ACTIVE_DATE_FORMAT" => "d.m.Y",
						"SET_TITLE" => "N",
						"SET_STATUS_404" => "N",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"HIDE_LINK_WHEN_NO_DETAIL" => "N",
						"PARENT_SECTION" => "",
						"PARENT_SECTION_CODE" => "",
						"INCLUDE_SUBSECTIONS" => "Y",
						"PAGER_TEMPLATE" => ".default",
						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "N",
						"PAGER_TITLE" => "",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
						"PAGER_SHOW_ALL" => "N",
						"AJAX_OPTION_ADDITIONAL" => "",
						"SET_BROWSER_TITLE" => "N",
						"SET_META_KEYWORDS" => "N",
						"SET_META_DESCRIPTION" => "N",
						"SHOW_DETAIL_LINK" => "Y",
						"COMPONENT_TEMPLATE" => "front-catalog-section",
						"SET_LAST_MODIFIED" => "N",
						"STRICT_SECTION_CHECK" => "N",
						"SHOW_SECTIONS" => "Y",
						"SHOW_GOODS" => "Y",
						"COMPOSITE_FRAME_MODE" => "A",
						"COMPOSITE_FRAME_TYPE" => "AUTO",
						"PAGER_BASE_LINK_ENABLE" => "N",
						"SHOW_404" => "N",
						"MESSAGE_404" => ""
					),
					false
				);?>
				</div>
			</div>
		</div>
	<?endif;?>
	<div class="row greyline">
		<div class="maxwidth-theme work-steps-wrapper">
			<div class="col-md-12">
				<div class="col-lg-4 col-md-4-col-sm-4 col-xs-4 work-steps">
					<div class="work-step-image-wrapper">
						<img class="work-step-image" src="/images/1.png" alt="" title="" />
					</div>
					<div class="work-step-title">Бесплатный выезд специалиста на замер</div>
					<!--<div class="work-step-desc">Выезд специалиста на замер</div>-->
				</div>
				<div class="col-lg-4 col-md-4-col-sm-4 col-xs-4 work-steps">
					<div class="work-step-image-wrapper">
						<img class="work-step-image" src="/images/2.png" alt="" title="" />
					</div>
					<div class="work-step-title">Расчет стоимости заказа</div>
					<!--<div class="work-step-desc">Предварительный расчет</div>-->
				</div>
				<div class="col-lg-4 col-md-4-col-sm-4 col-xs-4">
					<div class="work-step-image-wrapper">
						<img class="work-step-image" src="/images/3.png" alt="" title="" />
					</div>
					<div class="work-step-title">Заключение договора и монтаж</div>
					<!--<div class="work-step-desc">Согласование стоимости и заключение договора</div>-->
				</div>
				<?$GLOBALS['arServicesItemsFilter'] = array('!PROPERTY_SHOW_FRONT_PAGE' => false);?>
				<?$APPLICATION->IncludeComponent("bitrix:news.list", "front-teasers-pictures", array(
                    "IBLOCK_TYPE" => "aspro_stroy_content",
                        "IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_stroy_content"]["aspro_stroy_services"][0],
                        "NEWS_COUNT" => "5",
                        "SORT_BY1" => "SORT",
                        "SORT_ORDER1" => "ASC",
                        "SORT_BY2" => "ID",
                        "SORT_ORDER2" => "ASC",
                        "FILTER_NAME" => "arServicesItemsFilter",
                        "FIELD_CODE" => array(
                            0 => "NAME",
                            1 => "PREVIEW_TEXT",
                            2 => "PREVIEW_PICTURE",
                            3 => "",
                        ),
                        "PROPERTY_CODE" => array(
                            0 => "",
                            1 => "LINK",
                            2 => "ICON",
                            3 => "",
                        ),
                        "CHECK_DATES" => "Y",
                        "DETAIL_URL" => "",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600000",
                        "CACHE_FILTER" => "Y",
                        "CACHE_GROUPS" => "N",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "SET_TITLE" => "N",
                        "SET_STATUS_404" => "N",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "PAGER_TEMPLATE" => ".default",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "PAGER_TITLE" => "",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
                        "PAGER_SHOW_ALL" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "SHOW_DETAIL_LINK" => "Y",
                        "SET_BROWSER_TITLE" => "N",
                        "SET_META_KEYWORDS" => "N",
                        "SET_META_DESCRIPTION" => "N",
                        "COMPONENT_TEMPLATE" => "front-teasers-pictures",
                        "SET_LAST_MODIFIED" => "N",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "SHOW_404" => "N",
                        "MESSAGE_404" => "",
                        "STRICT_SECTION_CHECK" => "N",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO"
                    ),
                    false,
                    array(
                    "ACTIVE_COMPONENT" => "N"
                    )
                );?>
			</div>
		</div>
	</div><!--/row-->
	<?if($bCatalogFavoritesIndex):?>
		<?$GLOBALS['arCatalogItemsFilter'] = array('!PROPERTY_SHOW_ON_INDEX_PAGE' => false);?>
		<div class="greyline">
			<div class="row">
				<div class="maxwidth-theme">
					<div class="col-md-12">
						<?$APPLICATION->IncludeComponent("bitrix:news.list", "front-catalog", array(
                            "IBLOCK_TYPE" => "aspro_stroy_catalog",
                                "IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_stroy_catalog"]["aspro_stroy_catalog"][0],
                                "NEWS_COUNT" => "30",
                                "SORT_BY1" => "SORT",
                                "SORT_ORDER1" => "ASC",
                                "SORT_BY2" => "ID",
                                "SORT_ORDER2" => "ASC",
                                "FILTER_NAME" => "arCatalogItemsFilter",
                                "FIELD_CODE" => array(
                                    0 => "NAME",
                                    1 => "PREVIEW_PICTURE",
                                    2 => "DETAIL_PICTURE",
                                    3 => "",
                                ),
                                "PROPERTY_CODE" => array(
                                    0 => "",
                                    1 => "SHOW_ON_INDEX_PAGE",
                                    2 => "PRICE",
                                    3 => "PRICEOLD",
                                    4 => "STIKERS",
                                    5 => "SIZE",
                                    6 => "ARTICLE",
                                    7 => "APPOINTMENT",
                                    8 => "COLOR",
                                    9 => "",
                                ),
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "AJAX_OPTION_HISTORY" => "N",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "3600000",
                                "CACHE_FILTER" => "Y",
                                "CACHE_GROUPS" => "N",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                "SET_TITLE" => "N",
                                "SET_STATUS_404" => "N",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "INCLUDE_SUBSECTIONS" => "Y",
                                "PAGER_TEMPLATE" => ".default",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "PAGER_TITLE" => "",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
                                "PAGER_SHOW_ALL" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "SET_BROWSER_TITLE" => "N",
                                "SET_META_KEYWORDS" => "N",
                                "SET_META_DESCRIPTION" => "N",
                                "SHOW_DETAIL_LINK" => "Y",
                                "COMPONENT_TEMPLATE" => "front-catalog",
                                "SET_LAST_MODIFIED" => "N",
                                "SHOW_SECTIONS" => "Y",
                                "SHOW_GOODS" => "Y",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "SHOW_404" => "N",
                                "MESSAGE_404" => "",
                                "S_DETAIL_PRODUCT" => "Подробнее",
                                "SHOW_SLIDE_PROP" => array(
                                    0 => "",
                                    1 => "APPOINTMENT",
                                    2 => "COLOR",
                                )
                            ),
                            false,
                            array(
                            "ACTIVE_COMPONENT" => "N"
                            )
                        );?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>
	<div class="row">
		<div class="maxwidth-theme">
			<div class="col-md-12">
				<?$APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "front-news",
                    array(
                        "IBLOCK_TYPE" => "aspro_stroy_content",
                        "IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_stroy_content"]["aspro_stroy_news"][0],
                        "NEWS_COUNT" => "3",
                        "SORT_BY1" => "ACTIVE_FROM",
                        "SORT_ORDER1" => "DESC",
                        "SORT_BY2" => "SORT",
                        "SORT_ORDER2" => "ASC",
                        "FILTER_NAME" => "",
                        "FIELD_CODE" => array(
                            0 => "NAME",
                            1 => "PREVIEW_PICTURE",
                            2 => "DATE_ACTIVE_FROM",
                            3 => "",
                        ),
                        "PROPERTY_CODE" => array(
                            0 => "",
                            1 => "",
                        ),
                        "CHECK_DATES" => "Y",
                        "DETAIL_URL" => "",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600000",
                        "CACHE_FILTER" => "Y",
                        "CACHE_GROUPS" => "N",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "ACTIVE_DATE_FORMAT" => "j F Y",
                        "SET_TITLE" => "N",
                        "SET_STATUS_404" => "N",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "PAGER_TEMPLATE" => ".default",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "PAGER_TITLE" => "",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
                        "PAGER_SHOW_ALL" => "Y",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "SHOW_DETAIL_LINK" => "Y",
                        "SET_BROWSER_TITLE" => "Y",
                        "SET_META_KEYWORDS" => "Y",
                        "SET_META_DESCRIPTION" => "Y"
                    ),
                    false
                );?>
			</div>
		</div>
	</div>
	<?if($bPortfolioIndex):?>
		<div class="greyline">
			<div class="row">
				<div class="maxwidth-theme">
					<div class="col-md-12">
						<?$GLOBALS['arPropertyItemsFilter'] = array('!PROPERTY_SHOW_ON_INDEX_PAGE' => false);?>
						<?$APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "front-projects",
                            array(
                                "IBLOCK_TYPE" => "aspro_stroy_content",
                                "IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_stroy_content"]["aspro_stroy_projects"][0],
                                "NEWS_COUNT" => "7",
                                "SORT_BY1" => "SORT",
                                "SORT_ORDER1" => "ASC",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER2" => "ASC",
                                "FILTER_NAME" => "arPropertyItemsFilter",
                                "FIELD_CODE" => array(
                                    0 => "NAME",
                                    1 => "PREVIEW_TEXT",
                                    2 => "PREVIEW_PICTURE",
                                    3 => "",
                                ),
                                "PROPERTY_CODE" => array(
                                    0 => "",
                                    1 => "",
                                ),
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "AJAX_OPTION_HISTORY" => "N",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "3600000",
                                "CACHE_FILTER" => "Y",
                                "CACHE_GROUPS" => "N",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "ACTIVE_DATE_FORMAT" => "j F Y",
                                "SET_TITLE" => "N",
                                "SET_STATUS_404" => "N",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "INCLUDE_SUBSECTIONS" => "Y",
                                "PAGER_TEMPLATE" => ".default",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "PAGER_TITLE" => "",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
                                "PAGER_SHOW_ALL" => "Y",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "SHOW_DETAIL_LINK" => "Y",
                                "SET_BROWSER_TITLE" => "Y",
                                "SET_META_KEYWORDS" => "Y",
                                "SET_META_DESCRIPTION" => "Y",
                                "COMPONENT_TEMPLATE" => "front-projects",
                                "SET_LAST_MODIFIED" => "N",
                                "STRICT_SECTION_CHECK" => "N",
                                "SHOW_SECTIONS" => "Y",
                                "SHOW_GOODS" => "Y",
                                "COMPOSITE_FRAME_MODE" => "A",
                                "COMPOSITE_FRAME_TYPE" => "AUTO",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "SHOW_404" => "N",
                                "MESSAGE_404" => ""
                            ),
                            false
                        );?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>
	<div class="row">
		<div class="maxwidth-theme">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<?$GLOBALS['arCompanyItemsFilter'] = array('!PROPERTY_COMPANY_BLOCK' => false);?>
						<?$APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "front-company",
                            array(
                                "IBLOCK_TYPE" => "aspro_stroy_content",
                                "IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_stroy_content"]["aspro_stroy_company"][0],
                                "NEWS_COUNT" => "1",
                                "SORT_BY1" => "SORT",
                                "SORT_ORDER1" => "ASC",
                                "SORT_BY2" => "ID",
                                "SORT_ORDER2" => "DESC",
                                "FILTER_NAME" => "arCompanyItemsFilter",
                                "FIELD_CODE" => array(
                                    0 => "NAME",
                                    1 => "PREVIEW_TEXT",
                                    2 => "PREVIEW_PICTURE",
                                    3 => "",
                                ),
                                "PROPERTY_CODE" => array(
                                    0 => "",
                                    1 => "property1",
                                    2 => "property2",
                                    3 => "property3",
                                    4 => "property4",
                                ),
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "AJAX_OPTION_HISTORY" => "N",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "3600000",
                                "CACHE_FILTER" => "Y",
                                "CACHE_GROUPS" => "N",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "ACTIVE_DATE_FORMAT" => "j F Y",
                                "SET_TITLE" => "N",
                                "SET_STATUS_404" => "N",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "INCLUDE_SUBSECTIONS" => "Y",
                                "PAGER_TEMPLATE" => ".default",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "PAGER_TITLE" => "",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
                                "PAGER_SHOW_ALL" => "Y",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "SHOW_DETAIL_LINK" => "Y",
                                "SET_BROWSER_TITLE" => "Y",
                                "SET_META_KEYWORDS" => "Y",
                                "SET_META_DESCRIPTION" => "Y",
                                "COMPONENT_TEMPLATE" => "front-company",
                                "SET_LAST_MODIFIED" => "N",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "SHOW_404" => "N",
                                "MESSAGE_404" => ""
                            ),
                            false
                        );?>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="greyline">
        <div class="row">
            <div class="maxwidth-theme">
                <div class="col-md-12">
                    <?$APPLICATION->IncludeComponent("bitrix:news.list", "front-brands", array(
                        "IBLOCK_TYPE" => "aspro_stroy_content",
                        "IBLOCK_ID" => 31,
                        "NEWS_COUNT" => "30",
                        "SORT_BY1" => "SORT",
                        "SORT_ORDER1" => "ASC",
                        "SORT_BY2" => "ID",
                        "SORT_ORDER2" => "ASC",
                        "FILTER_NAME" => "",
                        "FIELD_CODE" => array(
                            0 => "NAME",
                            1 => "PREVIEW_PICTURE"
                        ),
                        "PROPERTY_CODE" => array(),
                        "CHECK_DATES" => "Y",
                        "DETAIL_URL" => "",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600000",
                        "CACHE_FILTER" => "Y",
                        "CACHE_GROUPS" => "N",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "SET_TITLE" => "N",
                        "SET_STATUS_404" => "N",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "PAGER_TEMPLATE" => ".default",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "PAGER_TITLE" => "",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
                        "PAGER_SHOW_ALL" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "SET_BROWSER_TITLE" => "N",
                        "SET_META_KEYWORDS" => "N",
                        "SET_META_DESCRIPTION" => "N",
                        "SHOW_DETAIL_LINK" => "N",
                        "COMPONENT_TEMPLATE" => "front-brands",
                        "SET_LAST_MODIFIED" => "N",
                        "SHOW_SECTIONS" => "Y",
                        "SHOW_GOODS" => "Y",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "SHOW_404" => "N",
                        "MESSAGE_404" => "",
                        "S_DETAIL_PRODUCT" => "Подробнее",
                        "SHOW_SLIDE_PROP" => array()
                    ),
                        false,
                        array(
                            "ACTIVE_COMPONENT" => "Y"
                        )
                    );?>
                </div>
            </div>
        </div>
    </div>
	<div class="color_block">
		<div class="row">
			<div class="maxwidth-theme">
				<div class="col-md-12">
					<div class="block front">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-3 col-sm-3">
											<?$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												"",
												Array(
													"AREA_FILE_SHOW" => "file",
													"PATH" => SITE_DIR."include/front-text1.php",
													"EDIT_TEMPLATE" => "standard.php"
												)
											);?>
										</div>
										<div class="col-md-7 col-sm-6">
											<?$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												"",
												Array(
													"AREA_FILE_SHOW" => "file",
													"PATH" => SITE_DIR."include/front-text2.php",
													"EDIT_TEMPLATE" => "standard.php"
												)
											);?>
										</div>
										<div class="col-md-2 col-sm-3">
											<?$APPLICATION->IncludeComponent(
												"bitrix:main.include",
												"",
												Array(
													"AREA_FILE_SHOW" => "file",
													"PATH" => SITE_DIR."include/front-text3.php",
													"EDIT_TEMPLATE" => "standard.php"
												)
											);?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?$indexProlog = ob_get_clean();?>

<?ob_start();?>	
<?$indexEpilog = ob_get_clean();?>