<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CMain $APPLICATION
 * @var CUser $USER
 */
CUtil::InitJSCore(array('ajax', 'fx', 'viewer'));
// ************************* Input params***************************************************************
$arParams["SHOW_LINK_TO_FORUM"] = ($arParams["SHOW_LINK_TO_FORUM"] == "N" ? "N" : "Y");
$arParams["FILES_COUNT"] = intval(intval($arParams["FILES_COUNT"]) > 0 ? $arParams["FILES_COUNT"] : 1);
$arParams["IMAGE_SIZE"] = (intval($arParams["IMAGE_SIZE"]) > 0 ? $arParams["IMAGE_SIZE"] : 100);
if (LANGUAGE_ID == 'ru'):
	$path = str_replace(array("\\", "//"), "/", dirname(__FILE__)."/ru/script.php");
	include($path);
endif;
// *************************/Input params***************************************************************
if (!empty($arResult["MESSAGES"])):
if ($arResult["NAV_RESULT"] && $arResult["NAV_RESULT"]->NavPageCount > 1):
?>



<div class="reviews-navigation-box reviews-navigation-top">
	<div class="reviews-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
	<div class="reviews-clear-float"></div>
</div>
<?
endif;

?>
<div class="reviews-block-container reviews-reviews-block-container" id="<?=$arParams["FORM_ID"]?>container">
	<div class="reviews-block-outer">
		<div class="reviews-block-inner">



					<div class="reviews-block">
			                <h2>Отзывы</h2> <span class="red-color count"><?=count($arResult["MESSAGES"])?></span>

			                <div id="reviews" class="reviews-row">
<?
$iCount = 0;
foreach ($arResult["MESSAGES"] as $res):
	$iCount++;
	?>
	<div class="item">

	<div class="review-item" itemscope itemtype="https://schema.org/Review">

			<h5 class="name" itemprop="author" itemscope itemtype="https://schema.org/Person"><?=$res["AUTHOR_NAME"]?></h5>
			<?$arParams["DATE_CREATE"]="j F Y";?>
			<div class="date" itemprop="datePublished" content=<?=$res["POST_DATE"]?>><?=CIBlockFormatProperties::DateFormat($arParams["DATE_CREATE"], MakeTimeStamp($res["POST_DATE"], CSite::GetDateFormat()));?></div>

			<div class="rating-row" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
				<?
				            $rating = getRatingByIdMessage($res['ID']);
										$id = getIdByIdMessage($res['ID']);
				            ?>
				            <div class="star_block starrr" id=""><?php
				                for ($i = 1; $i <= 5; $i++) {
				                    if ($i <= $rating) {
				                        echo ' <a href="#" class="fa-star fa rate" itemprop="ratingValue"></a>';
				                    } else {
				                        echo '<a href="#" class="fa-star fa fa-black" itemprop="ratingValue"></a>';
				                    }
				                }
											  ?>
				            </div>

                            <span class="red-color"><?=$rating?></span>
		</div>
		<table cellspacing="0" border="0" class="reviews-post-table <?=($iCount == 1 ? "reviews-post-first " : "")?><?
			?><?=($iCount == count($arResult["MESSAGES"]) ? "reviews-post-last " : "")?><?
			?><?=($iCount%2 == 1 ? "reviews-post-odd " : "reviews-post-even ")?><?
			?><?=(($res["APPROVED"] == 'Y') ? "" : "reviews-post-hidden")
			?>" bx-author-id="<?=$res["AUTHOR_ID"]?>" bx-author-name="<?=$res["AUTHOR_NAME"]?>" id="message<?=$res["ID"]?>">
			<tr class="reviews-actions">
				<td>
		<?
			if ($arResult["SHOW_POST_FORM"] == "Y")
			{
				?>
				<div class="reviews-post-reply-buttons"><noindex>
					<a href="#review_anchor" style='margin-left:0;' title="<?=GetMessage("F_NAME")?>" class="reviews-button-small" bx-act="reply"><?=GetMessage("F_NAME")?></a>
		<?			if ($arResult["FORUM"]["ALLOW_QUOTE"] == "Y")
					{
						?>
						<span class="separator"></span>
						<a href="#review_anchor" title="<?=GetMessage("F_QUOTE_HINT")?>" class="reviews-button-small" bx-act="quote"><?=GetMessage("F_QUOTE_FULL")?></a><?
					}

					if ($arResult["PANELS"]["DELETE"] == "Y")
					{
						?>
						<span class="separator"></span>
						<a rel="nofollow" href="<?=htmlspecialcharsbx($res["URL"]["~DELETE"])?>" class="reviews-button-small" bx-act="del"><?=GetMessage("F_DELETE")?></a>
		<?			}
					if ($arParams["SHOW_RATING"] == "Y")
					{
						?>
						<span class="rating_vote_text">
						<span class="separator"></span>
						<?
						$arRatingParams = Array(
								"ENTITY_TYPE_ID" => "FORUM_POST",
								"ENTITY_ID" => $res["ID"],
								"OWNER_ID" => $res["AUTHOR_ID"],
								"PATH_TO_USER_PROFILE" => strlen($arParams["PATH_TO_USER"]) > 0? $arParams["PATH_TO_USER"]: $arParams["~URL_TEMPLATES_PROFILE_VIEW"]
							);
						if (!isset($res['RATING']))
							$res['RATING'] = array(
									"USER_VOTE" => 0,
									"USER_HAS_VOTED" => 'N',
									"TOTAL_VOTES" => 0,
									"TOTAL_POSITIVE_VOTES" => 0,
									"TOTAL_NEGATIVE_VOTES" => 0,
									"TOTAL_VALUE" => 0
								);
						$arRatingParams = array_merge($arRatingParams, $res['RATING']);
						$GLOBALS["APPLICATION"]->IncludeComponent( "bitrix:rating.vote", $arParams["RATING_TYPE"], $arRatingParams, $component, array("HIDE_ICONS" => "Y"));
						?>
					</span><?
					}
		?>
				</noindex></div>
		<?
			}
		?>
				</td>
			</tr>

		</table>

		<div class="text-block" itemprop="reviewBody">
			<?=substr($res["POST_MESSAGE_TEXT"],0,200)?><div class="reviews-more-text"><?=substr($res["POST_MESSAGE_TEXT"],200)?></div></div>
			<?if (strlen($res["POST_MESSAGE_TEXT"])>200):?>
		<a class="red-color more-text">читать полностью</a>
		<?endif;?>

</div>
</div>

<?
endforeach;
?>
</div>
	<div class="buttons-row">
			<a id="show_reviews-reply-form">Оставить отзыв</a>
<a href="https://xn--24-mlcaoysjdt.xn--p1ai/company/reviews/">Смотреть все отзывы</a>
	</div>
		<script>
		$("#show_reviews-reply-form").click(function() {
	$( ".reviews-reply-form" ).show();
});
		</script>
</div>
		</div>
	</div>

<?

if (strlen($arResult["NAV_STRING"]) > 0 && $arResult["NAV_RESULT"]->NavPageCount > 1):
?>
<div class="reviews-navigation-box reviews-navigation-bottom">
	<div class="reviews-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
	<div class="reviews-clear-float"></div>
</div>
<?
endif;


if (!empty($arResult["read"]) && $arParams["SHOW_LINK_TO_FORUM"] != "N"):
?>
<div class="reviews-link-box">
	<div class="reviews-link-box-text">
		<a href="<?=$arResult["read"]?>"><?=GetMessage("F_C_GOTO_FORUM");?></a>
	</div>
</div>

<?
endif;

endif;

if (empty($arResult["ERROR_MESSAGE"]) && !empty($arResult["OK_MESSAGE"])):
?>
<div class="reviews-note-box reviews-note-note">
	<a name="reviewnote"></a>
	<div class="reviews-note-box-text"><?=ShowNote($arResult["OK_MESSAGE"]);?></div>
</div>
<?
endif;

if ($arResult["SHOW_POST_FORM"] != "Y"):
	return false;
endif;


if (!empty($arResult["MESSAGE_VIEW"])):
?>
<div class="reviews-preview">
<div class="reviews-header-box">
	<div class="reviews-header-title"><a name="postform"><span><?=GetMessage("F_PREVIEW")?></span></a></div>
</div>

<div class="reviews-info-box reviews-post-preview">
	<div class="reviews-info-box-inner">
		<div class="reviews-post-entry">
			<div class="reviews-post-text"><?=$arResult["MESSAGE_VIEW"]["POST_MESSAGE_TEXT"]?></div>
<?
		if (!empty($arResult["REVIEW_FILES"])):
?>
			<div class="reviews-post-attachments">
				<label><?=GetMessage("F_ATTACH_FILES")?></label>
<?
			foreach ($arResult["REVIEW_FILES"] as $arFile):
?>
				<div class="reviews-post-attachment"><?
				?><?$GLOBALS["APPLICATION"]->IncludeComponent(
					"bitrix:forum.interface", "show_file",
					Array(
						"FILE" => $arFile,
						"WIDTH" => $arResult["PARSER"]->image_params["width"],
						"HEIGHT" => $arResult["PARSER"]->image_params["height"],
						"CONVERT" => "N",
						"FAMILY" => "FORUM",
						"SINGLE" => "Y",
						"RETURN" => "N",
						"SHOW_LINK" => "Y"),
					null,
					array("HIDE_ICONS" => "Y"));
				?></div>
<?
			endforeach;
?>
			</div>
<?
		endif;
?>
		</div>
	</div>
</div>
<div class="reviews-br"></div>
</div>
<?
endif;
?>
<script type="text/javascript">
	BX.ready(function(){
		BX.message({
			no_topic_name : '<?=GetMessageJS("JERROR_NO_TOPIC_NAME")?>',
			no_message : '<?=GetMessageJS("JERROR_NO_MESSAGE")?>',
			max_len : '<?=GetMessageJS("JERROR_MAX_LEN")?>',
			f_author : ' <?=GetMessageJS("JQOUTE_AUTHOR_WRITES")?>:\n',
			f_cdm : '<?=GetMessageJS("F_DELETE_CONFIRM")?>',
			f_show : '<?=GetMessageJS("F_SHOW")?>',
			f_hide : '<?=GetMessageJS("F_HIDE")?>',
			f_wait : '<?=GetMessageJS("F_WAIT")?>',
			MINIMIZED_EXPAND_TEXT : '<?=CUtil::addslashes($arParams["MINIMIZED_EXPAND_TEXT"])?>',
			MINIMIZED_MINIMIZE_TEXT : '<?=CUtil::addslashes($arParams["MINIMIZED_MINIMIZE_TEXT"])?>'
		});
		BX.viewElementBind(BX('<?=$arParams["FORM_ID"]?>container'), {},
			function(node){
				return BX.type.isElementNode(node) && (node.getAttribute('data-bx-viewer') || node.getAttribute('data-bx-image'));
			}
		);
	});
</script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/starrr.js"></script>
<?
include(__DIR__."/form.php");
?>
