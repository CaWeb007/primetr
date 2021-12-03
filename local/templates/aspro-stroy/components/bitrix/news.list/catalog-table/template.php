<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);
$arParams["COUNT_IN_LINE"] = intval($arParams["COUNT_IN_LINE"]);
$arParams["COUNT_IN_LINE"] = (($arParams["COUNT_IN_LINE"] > 0 && $arParams["COUNT_IN_LINE"] < 12) ? $arParams["COUNT_IN_LINE"] : 3);
$colmd = floor(12 / $arParams['COUNT_IN_LINE']);
$colsm = floor(12 / round($arParams['COUNT_IN_LINE'] / 2));
$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
?>
<!--<div class="catalog item-views table">-->
	<?if($arResult["ITEMS"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?>
			<?=$arResult["NAV_STRING"]?>
		<?endif;?>

<!--		<div class="row items" itemscope itemtype="http://schema.org/ItemList">-->
			<?foreach($arResult["ITEMS"] as $arItem):?>
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
				// use order button?
				$bOrderButton = true;
				?>
				<?$arItem["DETAIL_PAGE_URL"] = CIBlock::ReplaceDetailUrl(str_replace('filter_search','catalog', $arParams["DETAIL_URL"]), $arItem, true, "E");?>
				<div class="col-md-<?=$colmd?> col-sm-<?=$colsm?> col-xs-<?=$colsm?>">
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

								<?/*
								<?// element section name?>
								<?if(strlen($arItem['SECTION_NAME'])):?>
									<div class="section_name"><?=$arItem['SECTION_NAME']?></div>
								<?endif;?>
								*/?>

								<?// element status?>
								<?/*if(strlen($arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
									<span class="label label-<?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="description"><?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
								<?endif;?>
								
								<?// element article?>
								<?if(strlen($arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
									<span class="article" itemprop="description"><?=GetMessage('S_ARTICLE')?>:�<span><?=$arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span></span>
								<?endif;*/?>
								
							</div>
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
										<?foreach($arItem['CHARACTERISTICS'] as $key=>$arChar){?>
											<div class="char_item">
												<span class="name"><?=$arChar["NAME"];?>: </span>
												<span class="value">
												<?if(is_array($arChar["VALUE"])):?>
													<?=implode('�/ ', $arChar["VALUE"]);?>
												<?else:?>
													<?=$arChar["VALUE"];?><?=(strpos($key, 'SIZE_') !== false ? GetMessage('SIZE_UNIT') : '')?>
												<?endif;?>
											</div>
										<?}?>
									</div>
								</div>
							<?}?>
							</div>
						</div>
					</div>
				</div>
			<?endforeach;?>
            <div class="catalog-items__nav">
                <div class="row">
                    <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
                        <?=$arResult["NAV_STRING"]?>
                    <?endif;?>
                </div>
            </div>
			<?// slice elements height?>
			<script type="text/javascript">
			var templateName = '<?=$templateName?>';
			$(document).ready(function(){
				if(!jQuery.browser.mobile){
					$('.catalog.item-views.table .item .image').sliceHeight({lineheight: -4});
					$('.catalog.item-views.table .item .title').sliceHeight();
					$('.catalog.item-views.table .item .cont').sliceHeight();
					$('.catalog.item-views.table .item .foot').sliceHeight();
					$('.catalog.item-views.table .item').sliceHeight({'classNull':'.footer_button'});
				}
			});
			</script>
<!--		</div>-->

	<?endif;?>
	
	<?if(is_array($arResult["SECTION"]["PATH"])):?>
		<?$arCurSectionPath = end($arResult["SECTION"]["PATH"]);?>
		<?if(strlen($arCurSectionPath["DESCRIPTION"]) && strpos($_SERVER["REQUEST_URI"], "PAGEN") === false):?>
			<div class="cat-desc"><hr style="<?=(strlen($arResult['NAV_STRING']) && $arParams['DISPLAY_BOTTOM_PAGER'] ? 'margin-top:20px;' : 'border-color:transparent;margin-top:0;margin-bottom:10px;')?>" /><?=$arCurSectionPath["DESCRIPTION"]?></div>
		<?endif;?>
	<?endif;?>
<!--</div>-->
<?$frame->end();?>