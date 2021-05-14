<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
CModule::IncludeModule('aspro.stroy');
$id = (isset($_REQUEST["id"]) ? $_REQUEST["id"] : false);
$arFrontParametrs = CStroy::GetFrontParametrsValues(SITE_ID);
$captcha = (in_array($arFrontParametrs['USE_CAPTCHA_FORM'], array('HIDDEN', 'IMAGE', 'RECAPTCHA')) ? $arFrontParametrs['USE_CAPTCHA_FORM'] : 'NONE');
$processing = ($arFrontParametrs['DISPLAY_PROCESSING_NOTE'] === 'Y' ? 'Y' : 'N');
$processing_checked = ($arFrontParametrs['PROCESSING_NOTE_CHECKED'] === 'Y' ? 'Y' : 'N');
$isCallBack = $id == CCache::$arIBlocks[SITE_ID]["aspro_stroy_form"]["aspro_corporation_callback"][0];
$isCalculate = $id == CCache::$arIBlocks[SITE_ID]["aspro_stroy_form"]["aspro_stroy_form_price"][0];
$successMessage = ($isCallBack ? "<p>Наш менеджер перезвонит вам в ближайшее время.</p><p>Спасибо за ваше обращение!</p>" : "Спасибо! Ваше сообщение отправлено!");
$template=($isCalculate ? "popup_cust" : "popup");
?>
<span class="jqmClose top-close fa fa-close"></span>
<?if($id):?>
	<?$APPLICATION->IncludeComponent(
		"aspro:form.stroy", $template,
		Array(
			"IBLOCK_TYPE" => "aspro_stroy_form",
			"IBLOCK_ID" => $id,
			"USE_CAPTCHA" => $captcha,
			"DISPLAY_PROCESSING_NOTE" => $processing,
			"PROCESSING_NOTE_CHECKED" => $processing_checked,			
			"AJAX_MODE" => "Y",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "100000",
			"AJAX_OPTION_ADDITIONAL" => "",
			//"IS_PLACEHOLDER" => "Y",
			"SUCCESS_MESSAGE" => $successMessage,
			"SEND_BUTTON_NAME" => "Отправить",
			"SEND_BUTTON_CLASS" => "btn btn-default",
			"DISPLAY_CLOSE_BUTTON" => "Y",
			"POPUP" => "Y",
			"CLOSE_BUTTON_NAME" => "Закрыть",
			"CLOSE_BUTTON_CLASS" => "jqmClose btn btn-default bottom-close"
		)
	);?>
<?endif;?>