<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<?if($arResult['ITEMS']):?>
	<?
	$qntyItems = count($arResult['ITEMS']);
	$countmd = 3;
	$countsm = 3;
	$countxs = 2;
	$countxsm = 1;
	$colmd = 4;
	$colsm = 3;
	$colxs = 6;
	$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
	?>
	<div class="catalog item-views table front linked">
		<div class="flexslider unstyled row" data-plugin-options='{"animation": "slide", "directionNav": true, "itemMargin":30, "controlNav" :true, "animationLoop": true, "slideshow": false, "counts": [<?=$countmd?>, <?=$countsm?>, <?=$countxs?>, <?=$countxsm?>]}'>
		<?/*<div class="flexslider unstyled row" data-plugin-options='{"animation": "slide", "directionNav": true, "controlNav" :true, "animationLoop": true, "slideshow": false, "counts": [<?=$countmd?>, <?=$countsm?>, <?=$countxs?>]}'>*/?>
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
						$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 160, 'height' => 160), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
						$imageSrc = ($bImage ? $arImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage_product.png');
						$imageDetailSrc = ($bImage ? $arItem['FIELDS']['DETAIL_PICTURE']['SRC'] : false);
					}
					// use order button?
					$bOrderButton = $arItem["DISPLAY_PROPERTIES"]["FORM_ORDER"]["VALUE_XML_ID"] == "YES";
					?>
					<li class="col-md-<?=$colmd?> col-sm-<?=$colsm?> col-xs-<?=$colxs?>">
						<div class="item<?=($bShowImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">
							<div>
							<?if($bShowImage):?>
								<div class="image">
									<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="blink" itemprop="url">
									<?elseif($imageDetailSrc):?><a href="<?=$imageDetailSrc?>" class="img-inside fancybox" itemprop="url">
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
											<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" itemprop="url"><?endif;?>
												<span itemprop="name"><?=$arItem['NAME']?></span>
											<?if($bDetailLink):?></a><?endif;?>
										</div>
									<?endif;?>
										
									<?// element section name?>
									<?/*if(strlen($arItem['SECTION_NAME'])):?>
										<div class="section_name"><?=$arItem['SECTION_NAME']?></div>
									<?endif;?>
									
									<?// element status?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
										<span class="label label-<?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="description"><?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
									<?endif;?>
									
									<?// element article?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
										<span class="article" itemprop="description"><?=GetMessage('S_ARTICLE')?>:&nbsp;<span><?=$arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span></span>
									<?endif;*/?>
									
									<?/*
									<?// element preview text?>
									<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
										<div class="description" itemprop="description">
											<?if($arItem['PREVIEW_TEXT_TYPE'] == 'text'):?>
												<p><?=$arItem['FIELDS']['PREVIEW_TEXT']?></p>
											<?else:?>
												<?=$arItem['FIELDS']['PREVIEW_TEXT']?>
											<?endif;?>
										</div>
									<?endif;?>
									*/?>
								</div>
								
								<?
								$isSizes=($arItem['DISPLAY_PROPERTIES']['SIZE']['VALUE'] ? true : false);
								$isPrices=(strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']) ? true : false);
								?>
								<?if($isPrices || $isSizes):?>
									<div class="row1 foot">
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
									</div>
								<?endif;?>
							</div>
						</div>
						</div>
					</li>
				<?endforeach;?>
			</ul>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.catalog.item-views.table.linked .item .image').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false, lineheight: -3});
			$('.catalog.item-views.table.linked .title').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
			$('.catalog.item-views.table.linked .cont').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
			$('.catalog.item-views.table.linked .foot').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
			$('.catalog.item-views.table.linked .item').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
		});
		</script>
	</div>
<?endif;?>