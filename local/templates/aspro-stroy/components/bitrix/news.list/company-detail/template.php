<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']){?>
<div class="company detail">
	<div class="items row">
		<?foreach($arResult['ITEMS'] as $key => $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			// use detail link?
			$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? $arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' : true);
			// preview image
			$bImage = strlen($arItem['FIELDS']['DETAIL_PICTURE']['SRC']);
			$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 300, 'height' => 300), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
			$imageSrc = ($bImage ? $arImage['src'] : false);
			$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
			?>
			<div class="col-md-12">
				<div class="item_block<?=($bImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
					<?// single detail image?>
					<?if($arItem['FIELDS']['DETAIL_PICTURE']):?>
						<?
						$atrTitle = (strlen($arItem['DETAIL_PICTURE']['DESCRIPTION']) ? $arItem['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arItem['DETAIL_PICTURE']['TITLE']) ? $arItem['DETAIL_PICTURE']['TITLE'] : $arItem['NAME']));
						$atrAlt = (strlen($arItem['DETAIL_PICTURE']['DESCRIPTION']) ? $arItem['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arItem['DETAIL_PICTURE']['ALT']) ? $arItem['DETAIL_PICTURE']['ALT'] : $arItem['NAME']));
						?>
						<?if($arItem['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'LEFT'):?>				
							<div class="detailimage image-left col-md-4 col-sm-4 col-xs-12"><a href="<?=$arItem['DETAIL_PICTURE']['SRC']?>" class="fancybox" title="<?=$atrTitle?>"><img src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" class="img-responsive" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></a></div>
						<?elseif($arItem['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'RIGHT'):?>
							<div class="detailimage image-right col-md-4 col-sm-4 col-xs-12"><a href="<?=$arItem['DETAIL_PICTURE']['SRC']?>" class="fancybox" title="<?=$atrTitle?>"><img src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" class="img-responsive" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></a></div>
						<?elseif($arItem['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'TOP'):?>
							<script type="text/javascript">
							$(document).ready(function() {
								$('section.page-top').remove();
								$('<div class="row"><div class="col-md-12"><div class="detailimage image-head"><img src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" class="img-responsive" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></div></div></div>').insertBefore('.body > .main > .container > .row');
							});
							</script>
						<?else:?>
							<div class="detailimage image-wide"><a href="<?=$arItem['DETAIL_PICTURE']['SRC']?>" class="fancybox" title="<?=$atrTitle?>"><img src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" class="img-responsive" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></a></div>
						<?endif;?>
					<?endif;?>
					<div class="content">
						<?// text?>
						<?if(strlen($arItem["FIELDS"]["DETAIL_TEXT"])):?>
							<div class="text">
								<?if($arItem["DETAIL_TEXT_TYPE"] == "text"):?>
									<p><?=$arItem["FIELDS"]["DETAIL_TEXT"];?></p>
								<?else:?>
									<?=$arItem["FIELDS"]["DETAIL_TEXT"];?>
								<?endif;?>
								<hr class="big" />
							</div>
						<?endif;?>

						<?/*tizers block start*/?>
						<?$useBrands = ('Y' == $arParams['DETAIL_BRAND_USE']);
						if($useBrands){?>
							<?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "stroy_list", array(
								"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
								"IBLOCK_ID" => $arParams['IBLOCK_ID'],
								"ELEMENT_ID" => $arItem['ID'],
								"ELEMENT_CODE" => "",
								"PROP_CODE" => $arParams["DETAIL_BRAND_PROP_CODE"],
								"CACHE_TYPE" => $arParams['CACHE_TYPE'],
								"CACHE_TIME" => $arParams['CACHE_TIME'],
								"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
								"ELEMENT_COUNT" => 4,
								"TITLE_BLOCK" => (strlen($arParams['T_PROFIT']) ? $arParams['T_PROFIT'] : GetMessage('T_PROFIT')),
								"WIDTH" => "60",
								"WIDTH_SMALL" => "60",
								"HEIGHT" => "60",
								"HEIGHT_SMALL" => "60",
								),
								$component,
								array("HIDE_ICONS" => "Y")
							);?>
						<?}?>
						<?/*tizers block end*/?>
                            <div class="row">
                                <div class="maxwidth-theme">
                                    <div class="col-md-12">
                                        <?$APPLICATION->IncludeComponent("bitrix:news.list", "company-brands", array(
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
                                            "COMPONENT_TEMPLATE" => "company-brands",
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

						<?/* stat block start */?>
						<?if(strlen($arItem['DISPLAY_PROPERTIES']['property1']['VALUE'])||strlen($arItem['DISPLAY_PROPERTIES']['property2']['VALUE'])||strlen($arItem['DISPLAY_PROPERTIES']['property3']['VALUE'])||strlen($arItem['DISPLAY_PROPERTIES']['property4']['VALUE'])):?>
							<h4 class="title_block"><?=(strlen($arParams['T_STAT']) ? $arParams['T_STAT'] : GetMessage('T_STAT'))?></h4>
							<div class="props row">
								<?for($i=1;$i<=4;$i++){?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['property'.$i]['VALUE'])):?>
										<div class="col-md-3 col-sm-6 col-xs-6">
												<div class="value spincrement1" data-value="<?=$arItem['DISPLAY_PROPERTIES']['property'.$i]['VALUE']?>">0</div>
												<div class="text"><?=$arItem['DISPLAY_PROPERTIES']['property'.$i]['NAME']?></div>
										</div>
									<?endif;?>
								<?}?>
							</div>
							<hr/>
						<?endif;?>
						<?/* stat block end */?>

						<?/* gallery block start */?>
						<?if($arItem['GALLERY']):?>
							<div class="wraps">		
								<h4 class="title_block"><?=(strlen($arParams['T_GALLERY']) ? $arParams['T_GALLERY'] : GetMessage('T_GALLERY'))?></h4>
								<div class="item-views portfolio front">	
									<div class="row" itemscope itemtype="http://schema.org/ItemList">
										<?foreach($arItem["GALLERY"] as $arPhoto){?>
											<div class="col-md-3 col-sm-4 col-xs-6">
												<div class="item wline">
													<a href="<?=$arPhoto["DETAIL"]["SRC"]?>" class="dark_block_animate fancybox_ext" rel="big_gallery" title="<?=$arPhoto["TITLE"];?>"></a>
													<div class="img_block scale_block_animate" style="background-image: url('<?=$arPhoto["PREVIEW"]["src"];?>');"></div>						
												</div>
											</div>
										<?}?>
									</div>
								</div>	
								<hr class="big" />	
							</div>
						<?endif;?>
						<?/* gallery block end */?>

						<?/* order block start */?>
						<?if($arItem['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'Y'):?>
							<div class="order-block">
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-5 valign">
										<span class="btn btn-default btn-lg" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_stroy_form']['aspro_stroy_question'][0]?>" data-name="order_services" data-autoload-service="<?=$arItem['NAME']?>"><span><?=(strlen($arParams['S_ORDER_SERVICE']) ? $arParams['S_ORDER_SERVICE'] : GetMessage('S_ORDER_SERVICE'))?></span></span>
									</div>
									<div class="col-md-8 col-sm-8 col-xs-7 valign">
										<div class="text">
											<?$APPLICATION->IncludeComponent(
												'bitrix:main.include',
												'',
												Array(
													'AREA_FILE_SHOW' => 'file',
													'PATH' => SITE_DIR.'include/ask_services.php',
													'EDIT_TEMPLATE' => ''
												)
											);?>
										</div>
									</div>
								</div>
							</div>
						<?endif;?>
						<?/* order block end */?>
					</div>
				</div>
			</div>
		<?endforeach;?>
	</div>
</div>
<?}?>