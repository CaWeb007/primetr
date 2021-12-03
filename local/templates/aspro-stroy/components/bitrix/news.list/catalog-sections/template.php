<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<?if($arResult['SECTIONS']):?>
	<?
	$qntyItems = count($arResult['SECTIONS']);
	$colmd = ($qntyItems > 1 ? 6 : 12);
	$colsm = 12;
	?>
<!--	<div class="item-views catalog sections">-->
<!--		<div class="items row">-->
			<?foreach($arResult['SECTIONS'] as $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$arSectionButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], 0, $arItem['ID'], array('SESSID' => false, 'CATALOG' => true));
				$this->AddEditAction($arItem['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// preview picture
				if($bShowSectionImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE'])){
					$bImage = strlen($arItem['PICTURE']);
					$arSectionImage = ($bImage ? CFile::ResizeImageGet($arItem['PICTURE'], array('width' => 256, 'height' => 192), BX_RESIZE_IMAGE_PROPORTIONAL, true) : array());
					$imageSectionSrc = ($bImage ? $arSectionImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage_sections.png');
				}
				?>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div>
					    <div class="item<?=($bShowSectionImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">
						<?// icon or preview picture?>
						<?if($bShowSectionImage):?>
							<div class="image">
								<a class="blink" itemprop="url" href="<?=$arItem['SECTION_PAGE_URL']?>">
									<img src="<?=$imageSectionSrc?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" class="img-responsive" itemprop="image"/>
								</a>
							</div>
						<?endif;?>
						
						<div class="text">
                            <div class="cont">
                                <?// section name?>
                                <?if(in_array('NAME', $arParams['FIELD_CODE'])):?>
                                    <div class="title" itemprop="url" class="color_link">
                                        <a href="<?=$arItem['SECTION_PAGE_URL']?>">
                                            <span itemprop="name"><?=$arItem['NAME']?></span>
                                        </a>
                                    </div>
                                <?endif;?>
                            </div>
                            <div class="row1 foot" style="height: 34px;">
                            </div>
							<?// section preview text?>
						</div>
					</div>
                    </div>
				</div>

			<?endforeach;?>
<!--		</div>-->
		<script type="text/javascript">
		$(document).ready(function(){
			$('.catalog.item-views.sections .item .title').sliceHeight();
			$('.catalog.item-views.sections .item').sliceHeight();
		});
		</script>
<!--	</div>-->
<?endif;?>