<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
$frame = $this->createFrame()->begin('');
$frame->setBrowserStorage(true);
?>
<?// element name?>
<?if($arParams['DISPLAY_NAME'] != 'N' && strlen($arResult['NAME'])):?>
	<h2 class="title_block"><?=$arResult['NAME']?></h2>
<?endif;?>

<?/* gallery block start */?>
<?if($arResult['GALLERY']):?>
	<div class="head projects">
		<div class="row">
			<?if($arResult['GALLERY']):?>
				<div class="col-md-12">
					<div class="row galery">
						<div class="inner">
							<div class="flexslider unstyled row" id="slider" data-plugin-options='{"animation": "slide", "directionNav": true, "controlNav" :false, "animationLoop": true, "sync": ".detail .galery #carousel", "slideshow": false, "counts": [1, 1, 1]}'>
								<ul class="slides items">
									<?$countAll = count($arResult['GALLERY']);?>
									<?foreach($arResult['GALLERY'] as $i => $arPhoto):?>
										<li class="col-md-1 col-sm-1 item">
											<a href="<?=$arPhoto['DETAIL']['SRC']?>" class="fancybox" rel="gallery" target="_blank" title="<?=$arPhoto['TITLE']?>">
												<img src="<?=$arPhoto['PREVIEW']['src']?>" class="img-responsive inline" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
												<span class="zoom">
													<i class="fa"></i>
												</span>
											</a>
										</li>
									<?endforeach;?>
								</ul>
							</div>
						</div>
						<script type="text/javascript">
						$(document).ready(function(){
							$('.detail .galery .item').sliceHeight({slice: <?=$countAll?>, lineheight: -3, opacity:'Y'});
							$('.detail .galery #carousel').flexslider({
								animation: 'slide',
								controlNav: false,
								animationLoop: true,
								slideshow: false,
								itemWidth: 100,
								itemMargin: 7.5,
								minItems: 2,
								maxItems: 4,
								// asNavFor: '.detail .galery #slider'
							});
						});
						</script>
					</div>
				</div>
			<?endif;?>
		</div>
	</div>
<?endif;?>
<?/* gallery block end */?>

<?/* ask question block start */?>
<?if($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
	<div class="ask_a_question">
		<div class="inner">
			<span class="btn btn-default" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_stroy_form']['aspro_stroy_question'][0]?>" data-autoload-need_product="<?=$arResult['NAME']?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span></span>
			<!--
			<div class="margin-bottom-20">
				<?/*$APPLICATION->IncludeComponent(
					 'bitrix:main.include',
					 '',
					 Array(
						  'AREA_FILE_SHOW' => 'file',
						  'PATH' => SITE_DIR.'include/ask_question.php',
						  'EDIT_TEMPLATE' => ''
					 )
				);*/?>
			</div>
			-->
		</div>
	</div>
<?endif;?>
<?/* ask question block end */?>

<?if(strlen($arResult['FIELDS']['DETAIL_TEXT'])):?>
	<div class="content">
		<?// element detail text?>
		<?if($arResult['DETAIL_TEXT_TYPE'] == 'text'):?>
			<p><?=$arResult['FIELDS']['DETAIL_TEXT'];?></p>
		<?else:?>
			<?=$arResult['FIELDS']['DETAIL_TEXT'];?>
		<?endif;?>
	</div>
<?endif;?>

<?/*characteristics and docs files block start*/?>
<?if($arResult['CHARACTERISTICS'] || $arResult['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE']):?>
	<div class="row">
		<?// characteristics?>
		<?if($arResult['CHARACTERISTICS']):?>
			<div class="col-md-<?=($arResult['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE'] ? '8' : '8')?>">
				<div class="wraps">
					<h4 class="title_block"><?=(strlen($arParams['T_CHARACTERISTICS']) ? $arParams['T_CHARACTERISTICS'] : GetMessage('T_CHARACTERISTICS'))?></h4>
					<div class="chars">
						<div class="char-wrapp">
							<table class="props_table">
								<?foreach($arResult['CHARACTERISTICS'] as $arProp):?>
									<tr class="char">
										<td class="char_name <?=($arProp['HINT'] ? 'whint' : '');?>">
											<span>
												<?=$arProp['NAME']?>
												<?if($arProp['HINT']):?>
													<div class="hint">
														<span class="icons" data-toggle="tooltip" data-placement="top" title="<?=$arProp['HINT']?>"><i>?</i></span>
													</div>
												<?endif;?>
											</span>
										</td>
										<td class="char_value">
											<span>
												<?if(is_array($arProp['DISPLAY_VALUE'])):?>
													<?foreach($arProp['DISPLAY_VALUE'] as $key => $value):?>
														<?if($arProp['DISPLAY_VALUE'][$key + 1]):?>
															<?=$value.'&nbsp;/ '?>
														<?else:?>
															<?=$value?>
														<?endif;?>
													<?endforeach;?>
												<?else:?>
													<?=$arProp['DISPLAY_VALUE']?>
												<?endif;?>
											</span>
										</td>
									</tr>
								<?endforeach;?>
							</table>
						</div>
					</div>
				</div>
			</div>
		<?endif;?>
		<?// docs files?>
		<?if($arResult['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE']):?>
			<div class="col-md-<?=($arResult['CHARACTERISTICS'] ? '4' : '12')?>">
				<div class="wraps">
					<h4 class="title_block"><?=(strlen($arParams['T_DOCS']) ? $arParams['T_DOCS'] : GetMessage('T_DOCS'))?></h4>
					<div class="row docs">
						<?foreach($arResult['PROPERTIES']['DOCUMENTS']['VALUE'] as $docID):?>
							<?$arItem = CStroy::get_file_info($docID);?>
							<div class="col-md-12 item">
								<?
								$fileName = substr($arItem['ORIGINAL_NAME'], 0, strrpos($arItem['ORIGINAL_NAME'], '.'));
								$fileTitle = (strlen($arItem['DESCRIPTION']) ? $arItem['DESCRIPTION'] : $fileName);
								?>
								<div class="icons  <?=$arItem['TYPE']?>">
									<a href="<?=$arItem['SRC']?>" target="_blank" title="<?=$fileTitle?>"></a>
								</div>
								<div class="text">
									<a href="<?=$arItem['SRC']?>" target="_blank" title="<?=$fileTitle?>"><?=$fileTitle?></a>
									<?=GetMessage('CT_NAME_SIZE')?>:
									<span><?=CStroy::filesize_format($arItem['FILE_SIZE']);?></span>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
			</div>
		<?endif;?>
	</div>
	<hr />
<?endif;?>
<?/*characteristics and docs files block end*/?>

<?// gallery?>
<?if($arResult['GALLERY_BIG']):?>
	<div class="wraps">
		<h4 class="title_block"><?=(strlen($arParams['T_GALLERY']) ? $arParams['T_GALLERY'] : GetMessage('T_GALLERY'))?></h4>
		<div class="item-views portfolio front">
			<div class="row" itemscope itemtype="http://schema.org/ItemList">
				<?foreach($arResult["GALLERY_BIG"] as $arPhoto){?>
					<div class="col-md-3 col-sm-4 col-xs-6">
						<div class="item wline">
							<a href="<?=$arPhoto["DETAIL"]["SRC"]?>" class="dark_block_animate fancybox_ext" rel="big_gallery" title="<?=$arPhoto["TITLE"];?>"></a>
							<div class="img_block scale_block_animate" style="background-image: url('<?=$arPhoto["PREVIEW"]["src"];?>');"></div>
						</div>
					</div>
				<?}?>
			</div>
		</div>
		<hr />
	</div>
<?endif;?>

<?// reviews links?>
<?if($arResult["REVIEWS"]){?>
	<div class="wraps nomargin">
		<h4 class="title_block"><?=(strlen($arParams['T_REVIEWS']) ? $arParams['T_REVIEWS'] : GetMessage('T_REVIEWS'))?></h4>
		<div class="item-views image_left reviews">
			<div class="row items">
				<?$count = count($arResult["REVIEWS"]);?>
				<?foreach($arResult["REVIEWS"] as $arItem):?>
					<?
					// edit/add/delete buttons for edit mode
					$arItemButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], $arItem['ID'], 0, array('SESSID' => false, 'CATALOG' => true));
					$this->AddEditAction($arItem['ID'], $arItemButtons['edit']['edit_element']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
					$this->AddDeleteAction($arItem['ID'], $arItemButtons['edit']['delete_element']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
					<div class="col-md-<?=(($count >1) ? '12' : '12');?> col-sm-<?=(($count >1) ? '12' : '12');?>">
						<div class="item review" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
							<div class="it">
								<div class="text"><?=$arItem['PREVIEW_TEXT']?></div>
								<?if($arItem['PROPERTY_DOCUMENTS_VALUE']):?>
									<div class="row docs">
										<?$arItem['PROPERTY_DOCUMENTS_VALUE'] = is_array($arItem['PROPERTY_DOCUMENTS_VALUE']) ? $arItem['PROPERTY_DOCUMENTS_VALUE'] : array($arItem['PROPERTY_DOCUMENTS_VALUE']);?>
										<?foreach($arItem['PROPERTY_DOCUMENTS_VALUE'] as $docID):?>
											<?$arFile = CStroy::get_file_info($docID);?>
											<div class="col-md-6 item">
												<?
												$fileName = substr($arFile['ORIGINAL_NAME'], 0, strrpos($arFile['ORIGINAL_NAME'], '.'));
												$fileTitle = (strlen($arFile['DESCRIPTION']) ? $arFile['DESCRIPTION'] : $fileName);
												?>
												<div class="icons  <?=$arFile['TYPE']?>">
													<a href="<?=$arItem['SRC']?>" target="_blank" title="<?=$fileTitle?>"></a>
												</div>
												<div class="text">
													<a href="<?=$arFile['SRC']?>" target="_blank" title="<?=$fileTitle?>"><?=$fileTitle?></a>
													<?=GetMessage('CT_NAME_SIZE')?>:
													<?=CStroy::filesize_format($arFile['FILE_SIZE']);?>
												</div>
											</div>
										<?endforeach;?>
									</div>
								<?endif;?>
								<div class="border"></div>
							</div>
							<div class="info">
								<div class="title"><?=$arItem['NAME']?></div>
								<?if($arItem['PROPERTY_POST_VALUE']):?>
									<div class="post"><?=$arItem['PROPERTY_POST_VALUE']?></div>
								<?endif;?>
							</div>
						</div>
					</div>
				<?endforeach;?>
			</div>
		</div>
		<hr />
	</div>
<?}?>

<?/* order block start */?>
<?if($arResult['DISPLAY_PROPERTIES']['FORM_PROJECT']['VALUE_XML_ID'] == 'YES'):?>
	<div class="order-block">
		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-5 valign">
				<span class="btn btn-default btn-lg" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_stroy_form']['aspro_stroy_order_project'][0]?>" data-name="order_project" data-autoload-project="<?=$arResult['NAME']?>"><?=(strlen($arParams['S_ORDER_PROJECT']) ? $arParams['S_ORDER_PROJECT'] : GetMessage('S_ORDER_PROJECT'))?></span>
			</div>
			<div class="col-md-8 col-sm-8 col-xs-7 valign">
				<div class="text">
					<?$APPLICATION->IncludeComponent(
						'bitrix:main.include',
						'',
						Array(
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_DIR.'include/ask_project.php',
							'EDIT_TEMPLATE' => ''
						)
					);?>
				</div>
			</div>
		</div>
	</div>
<?endif;?>
<?/* order block end */?>

<?// video?>
<?if($arResult['VIDEO']):?>
	<div class="wraps">
		<h4 class="title_block"><?=(strlen($arParams['T_VIDEO']) ? $arParams['T_VIDEO'] : GetMessage('T_VIDEO'))?></h4>
		<div class="row video">
			<?foreach($arResult['VIDEO'] as $i => $arVideo):?>
				<div class="col-md-6 item">
					<div class="video_body">
						<video id="js-video_<?=$i?>" width="350" height="217"  class="video-js" controls="controls" preload="metadata" data-setup="{}">
							<source src="<?=$arVideo["path"]?>" type='video/mp4' />
							<p class="vjs-no-js">
								To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video
							</p>
						</video>
					</div>
					<div class="title"><?=(strlen($arVideo["title"]) ? $arVideo["title"] : $i)?></div>
				</div>
			<?endforeach;?>
		</div>
	</div>
	<hr />
<?endif;?>
<?$frame->end();?>