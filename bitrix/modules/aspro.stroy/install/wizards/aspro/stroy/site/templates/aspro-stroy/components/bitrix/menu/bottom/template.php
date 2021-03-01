<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$this->setFrameMode(true);
$colmd = 4;
$colsm = 6;
?>
<?
if(!function_exists("ShowSubItemsExt")){
	function ShowSubItemsExt($arItem){?>
		<?if($arItem["CHILD"] && $arItem["DEPTH_LEVEL"]<=2):?>
			<?$noMoreSubMenuOnThisDepth = false;
			$dLevel=2;?>
			<ul class="submenu">
				<?foreach($arItem["CHILD"] as $arSubItem):?>
					<li class="<?=($arSubItem["SELECTED"] ? "active" : "")?>">
						<a href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>
						<?//if(!$noMoreSubMenuOnThisDepth):?>
						<?if($arSubItem["DEPTH_LEVEL"]<$dLevel):?>
							<?ShowSubItemsExt($arSubItem);?>
						<?endif;?>
					</li>
					<?//$noMoreSubMenuOnThisDepth |= CStroy::isChildsSelected($arSubItem["CHILD"]);?>
				<?endforeach;?>
			</ul>
		<?endif;?>
		<?
	}
}
?>
<?if($arResult):?>
	<div class="bottom-menu">
		<div class="items">
			<?foreach($arResult as $arItem):?>
				<?$bLink = strlen($arItem['LINK']);?>
				<div class="item<?=($arItem["SELECTED"] ? " active" : "")?>">
					<div class="title">
						<?if($bLink):?>
							<a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a>
						<?else:?>
							<span><?=$arItem['TEXT']?></span>
						<?endif;?>
						<?ShowSubItemsExt($arItem);?>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>