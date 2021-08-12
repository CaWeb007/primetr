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
	$countmd = 6;
	$countsm = 4;
	$countxs = 3;
	$countxsm = 2;
	$colmd = 2;
	$colsm = 3;
	$colxs = 4;
	?>
	<div class="catalog item-views table front front-brands" style="display:none;">
		<div class="top_wrapper_block nomargin_bottom">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => SITE_DIR."include/front-brands.php",
					"EDIT_TEMPLATE" => "standard.php"
				)
			);?>
			<?/*<a href="<?=str_replace('#SITE'.'_DIR#', SITE_DIR, $arResult['LIST_PAGE_URL'])?>" class="btn btn-default white transparent"><span><?=GetMessage("ALL_ITEMS");?></span></a>*/?>
			<div class="flexslider unstyled row" data-plugin-options='{"animation": "slide", "directionNav": true, "itemMargin":10, "controlNav" :true, "animationLoop": true, "slideshow": false, "counts": [<?=$countmd?>, <?=$countsm?>, <?=$countxs?>, <?=$countxsm?>]}'>
				<ul class="slides" itemscope itemtype="http://schema.org/ItemList">
					<?foreach($arResult["ITEMS"] as $i => $arItem):?>
                        <?if(empty($arItem['FIELDS']['PREVIEW_PICTURE'])) continue;?>
						<?
						// edit/add/delete buttons for edit mode
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						// use detail link?
						$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? $arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' : true);
						// preview image
							$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
							$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 200, 'height' => 192), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
							$imageSrc = ($bImage ? $arImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage_product.png');
							$imageDetailSrc = ($bImage ? $arItem['FIELDS']['DETAIL_PICTURE']['SRC'] : false);
						?>
						<li class="col-md-<?=$colmd?> col-sm-<?=$colsm?> col-xs-<?=$colxs?>">
							<div class="item<?=($bShowImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">
								<div>
									<div class="image">
										<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="blink" itemprop="url">
										<?elseif($imageDetailSrc):?><a href="javascript:voiu(0)" alt="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-inside fancybox" itemprop="url">
										<?endif;?>
											<img class="img-responsive" src="<?=$imageSrc?>" alt="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" itemprop="image" />
										<?if($bDetailLink):?></a>
										<?elseif($imageDetailSrc):?><span class="zoom"><i class="fa fa-16 fa-white-shadowed fa-search"></i></span></a>
										<?endif;?>
										<?/*if($arItem['DISPLAY_PROPERTIES']['STIKERS']['VALUE']):?>
											<div class="wrap_stickers">
												<div class="stickers">
													<?foreach($arItem["DISPLAY_PROPERTIES"]["STIKERS"]["VALUE_XML_ID"] as $key=>$class){?>
														<div class="sticker_<?=strtolower($class);?>"><?=$arItem["DISPLAY_PROPERTIES"]["STIKERS"]["VALUE"][$key]?></div>
													<?}?>
												</div>
											</div>
										<?endif;*/?>
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
			$('.catalog.item-views.table.front').show();
			InitFlexSlider();
			$('.catalog.item-views.table.front .blink img').blink();

	}
	catch(e){}
});
</script>
<?$frame->end();?>