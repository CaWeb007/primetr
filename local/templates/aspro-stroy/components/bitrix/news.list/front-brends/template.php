<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);
?>
<?if($arResult['ITEMS']):?>
	<?
	$qntyItems = count($arResult['ITEMS']);
	$countmd = 4;
	$countsm = 3;
	$countxs = 2;
	$countxsm = 1;
	$colmd = 3;
	$colsm = 4;
	$colxs = 6;
	$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
	?>
	<div class="catalog item-views table front" style="display:none;">
		<div class="top_wrapper_block nomargin_bottom">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => SITE_DIR."include/front-catalog-favorites.php",
					"EDIT_TEMPLATE" => "standard.php"
				)
			);?>
			<a href="<?=str_replace('#SITE'.'_DIR#', SITE_DIR, $arResult['LIST_PAGE_URL'])?>" class="btn btn-default white transparent"><span><?=GetMessage("ALL_ITEMS");?></span></a>
			<div class="flexslider unstyled row" data-plugin-options='{"animation": "slide", "directionNav": true, "itemMargin":30, "controlNav" :true, "animationLoop": true, "slideshow": false, "counts": [<?=$countmd?>, <?=$countsm?>, <?=$countxs?>, <?=$countxsm?>]}'>
				<ul class="slides" itemscope itemtype="http://schema.org/ItemList">
					<?foreach($arResult["ITEMS"] as $i => $arItem):?>
						<?
						// edit/add/delete buttons for edit mode
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						// use detail link?
						$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? $arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' : true);
						// preview image
						if($bShowImage){
							$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
							$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 256, 'height' => 192), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
							$imageSrc = ($bImage ? $arImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage_product.png');
							$imageDetailSrc = ($bImage ? $arItem['FIELDS']['DETAIL_PICTURE']['SRC'] : false);
						}
						?>
						<li class="col-md-<?=$colmd?> col-sm-<?=$colsm?> col-xs-<?=$colxs?>">
							<div class="item<?=($bShowImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">
								<div>
								<?if($bShowImage):?>
									<div class="image">
										<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="blink" itemprop="url">
										<?elseif($imageDetailSrc):?><a href="<?=$imageDetailSrc?>" alt="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-inside fancybox" itemprop="url">
										<?endif;?>
											<img class="img-responsive" src="<?=$imageSrc?>" alt="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" itemprop="image" />
										<?if($bDetailLink):?></a>
										<?elseif($imageDetailSrc):?><span class="zoom"><i class="fa fa-16 fa-white-shadowed fa-search"></i></span></a>
										<?endif;?>
										<?if($arItem['DISPLAY_PROPERTIES']['STIKERS']['VALUE']):?>
											<div class="wrap_stickers">
												<div class="stickers">
													<?foreach($arItem["DISPLAY_PROPERTIES"]["STIKERS"]["VALUE_XML_ID"] as $key=>$class){?>
														<div class="sticker_<?=strtolower($class);?>"><?=$arItem["DISPLAY_PROPERTIES"]["STIKERS"]["VALUE"][$key]?></div>
													<?}?>
												</div>
											</div>
										<?endif;?>
									</div>
								<?endif;?>
								
								<div class="text">
									<div class="cont">
										<?// element name?>
										<?if(strlen($arItem['FIELDS']['NAME'])):?>
											<div class="title">
												<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" itemprop="url" class="color_link"><?endif;?>
													<span itemprop="name"><?=$arItem['NAME']?></span>
												<?if($bDetailLink):?></a><?endif;?>
											</div>
										<?endif;?>
											
										<?// element section name?>
										<?if(strlen($arItem['SECTION_NAME'])):?>
											<div class="section_name"><?=$arItem['SECTION_NAME']?></div>
										<?endif;?>										
									</div>

									<?// element status?>
									<?/*if(strlen($arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
										<span class="label label-<?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="description"><?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
									<?endif;?>
									
									<?// element article?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
										<span class="article" itemprop="description"><?=GetMessage('S_ARTICLE')?>:&nbsp;<span><?=$arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span></span>
									<?endif;*/?>
									<?
									$isSizes=($arItem['DISPLAY_PROPERTIES']['SIZE']['VALUE'] ? true : false);
									$isPrices=(strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']) ? true : false);
									?>
									<div class="row1 foot">
										<?if($isPrices || $isSizes):?>
												<?if($isSizes){?>
													<div class="item_f <?=(!$isPrices ? "" : "wprice");?><?//=(!$isPrices ? 'col-md-12 col-sm-12 col-xs-12' : 'col-md-4 col-sm-4 col-xs-12')?>">
															<div class="size_block">
																<?=$arItem['DISPLAY_PROPERTIES']['SIZE']['VALUE'];?><?=GetMessage('SIZE_UNIT');?>
															</div>
													</div>
												<?}?>
												<?// element price?>
												<?if($isPrices):?>
													<div class="item_f <?//=(!$isSizes ? 'col-md-12 col-sm-12 col-xs-12' : 'col-md-8 col-sm-8 col-xs-12 ws')?>">
														<div class="price pull-left1" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
															<div class="price_new">
																<span class="price_val"><?=CStroy::FormatPriceShema($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'])?></span>
															</div>
															<?if($arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']):?>
																<div class="price_old">
																	<span class="price_val"><?=$arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']?></span>
																</div>
															<?endif;?>
														</div>
													</div>
												<?endif;?>											
										<?endif;?>
									</div>
									<?if($arItem['CHARACTERISTICS']){?>
										<div class="footer_button">
											<div class="char">
												<?$i=0;?>
												<?foreach($arItem['CHARACTERISTICS'] as $key=>$arChar){													
													if($i>=2)
														continue;?>
													<div class="char_item">
														<span class="name"><?=$arChar["NAME"];?>: </span>
														<span class="value">
														<?if(is_array($arChar["VALUE"])):?>
															<?=implode('&nbsp;/ ', $arChar["VALUE"]);?>
														<?else:?>
															<?=$arChar["VALUE"];?><?=(strpos($key, 'SIZE_') !== false ? '&nbsp'.GetMessage('SIZE_UNIT') : '')?>
														<?endif;?>
													</div>
													<?$i++;?>
												<?}?>
											</div>
										</div>
									<?}?>
									<?/*?>
									<div class="footer_button">								
										<a class="btn btn-default btn-sm white" href="<?=$arItem['DETAIL_PAGE_URL'];?>"><?=(strlen($arParams['S_DETAIL_PRODUCT']) ? $arParams['S_DETAIL_PRODUCT'] : GetMessage('TO_ALL'))?></a>
									</div>
									*/?>
									</div>
								</div>
							</div>
						</li>
					<?endforeach;?>
				</ul>
			</div>
			<script type="text/javascript">
			$(document).ready(function(){
				$('.catalog.item-views.table .item .image').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false, lineheight: -4});
				// $('.catalog.item-views.table .title').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
				$('.catalog.item-views.table .cont').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
				$('.catalog.item-views.table .foot').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
				$('.catalog.item-views.table .item').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false, classNull: '.footer_button'});
			});
			</script>
		</div>
	</div>
<?endif;?>
<script type="text/javascript">
$(document).ready(function() {
	try{
		if(arStroyOptions.THEME.CATALOG_FAVORITES_INDEX == 'Y'){
			$('.catalog.item-views.table.front').show();
			/*if(arStroyOptions.THEME.TEASERS_INDEX == 'NONE' && arStroyOptions.THEME.CATALOG_INDEX == 'N'){
				$('.catalog.item-views.table.front').css('margin-top', '47px');
			}*/
			InitFlexSlider();
			$('.catalog.item-views.table.front .blink img').blink();
		}
		else{
			$('.catalog.item-views.table.front').remove();
		}
	}
	catch(e){}
});
</script>
<?$frame->end();?>