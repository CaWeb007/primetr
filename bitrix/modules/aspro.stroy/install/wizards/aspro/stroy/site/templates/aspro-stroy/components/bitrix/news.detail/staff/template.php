<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$this->setFrameMode(true);
if($arParams["DISPLAY_PICTURE"] != "N"){
	$arPhoto = ($arResult["FIELDS"]["DETAIL_PICTURE"] ? $arResult["FIELDS"]["DETAIL_PICTURE"] : $arResult["FIELDS"]["PREVIEW_PICTURE"]);
	if($arPhoto){
		$arImgs[] = array(
			'DETAIL' => $arPhoto,
			'PREVIEW' => CFile::ResizeImageGet($arPhoto["ID"], array('width' => 300, 'height' => 300), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true),
			'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arResult['NAME'])),
			'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME'])),
		);
	}
}
?>
<div class="detail item-views list image_left staff <?=($templateName = $component->{"__parent"}->{"__template"}->{"__name"})?>">
	<article>
		<?if($arImgs):?>
			<?ob_start();?>
				<div class="image">
					<img src="<?=$arImgs[0]["DETAIL"]["SRC"]?>" title="<?=$arImgs[0]["TITLE"]?>" alt="<?=$arImgs[0]["ALT"]?>" class="img-responsive" />
				</div>
			<?$imagePart = ob_get_clean();?>
		<?endif;?>

		<?ob_start();?>
			<?// element name?>
			<?if($arParams["DISPLAY_NAME"] != "N"):?>
				<div class="title">
					<?=$arResult["NAME"]?>
				</div>
			<?endif;?>
			
			<?// element post?>										
			<?if(strlen($arResult['DISPLAY_PROPERTIES']['POST']['VALUE'])):?>
				<div class="post"><?=$arResult['DISPLAY_PROPERTIES']['POST']['VALUE']?></div>
				<?unset($arResult['DISPLAY_PROPERTIES']['POST']);?>
			<?endif;?>
			
			<?// element preview text?>
			<?if(strlen($arResult['FIELDS']['PREVIEW_TEXT'])):?>
				<?if($arResult['PREVIEW_TEXT_TYPE'] == 'text'):?>
					<p><?=$arResult['FIELDS']['PREVIEW_TEXT']?></p>
				<?else:?>
					<?=$arResult['FIELDS']['PREVIEW_TEXT']?>
				<?endif;?>
			<?endif;?>
			
			<?// element display properties?>
			<?if($arResult['DISPLAY_PROPERTIES']):?>
				<hr/>
				<div class="properties">
					<?foreach($arResult['DISPLAY_PROPERTIES'] as $PCODE => $arProperty):?>
						<div class="property">
							<?if($arProperty['XML_ID']):?>
								<i class="fa <?=$arProperty['XML_ID']?>"></i>&nbsp;
							<?else:?>
								<?=$arProperty['NAME']?>:&nbsp;
							<?endif;?>
							<?if(is_array($arProperty['DISPLAY_VALUE'])):?>
								<?$val = implode('&nbsp;/&nbsp;', $arProperty['DISPLAY_VALUE']);?>
							<?else:?>
								<?$val = $arProperty['DISPLAY_VALUE'];?>
							<?endif;?>
							<?if($PCODE == 'SITE'):?>
								<!--noindex-->
								<?=str_replace("href=", "rel='nofollow' class='color_link' target='_blank' href=", $val);?>
								<!--/noindex-->
							<?elseif($PCODE == 'EMAIL'):?>
								<a class="color_link" href="mailto:<?=$val?>"><?=$val?></a>
							<?else:?>
								<?=$val?>
							<?endif;?>
						</div>
					<?endforeach;?>
				</div>
			<?endif;?>
		<?$textPart = ob_get_clean();?>

		<div class="item<?=($arImgs ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arResult['ID'])?>">
			<div class="row">
				<?if(!$arImgs):?>
					<div class="col-md-12"><div class="text"><?=$textPart?></div></div>
				<?else:?>
					<div class="col-md-3 col-sm-3 col-xs-12"><?=$imagePart?></div>
					<div class="col-md-9 col-sm-9 col-xs-12"><div class="text"><?=$textPart?></div></div>
				<?endif;?>
			</div>
		</div>
	</article>
</div>