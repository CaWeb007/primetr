<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Адрес и номер телефона компании «Периметр»");
$APPLICATION->SetTitle("Контакты");?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.store.list", 
	"store_list",
	array(
		"COMPONENT_TEMPLATE" => "store-list",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"PHONE" => "Y",
		"SCHEDULE" => "Y",
		"PATH_TO_ELEMENT" => "store/#store_id#",
		"MAP_TYPE" => "0",
		"SET_TITLE" => "N",
		"TITLE" => ""
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>