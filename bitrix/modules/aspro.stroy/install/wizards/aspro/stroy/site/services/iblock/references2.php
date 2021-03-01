<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)	die();

if(!defined("WIZARD_SITE_ID")) return;
if(!defined("WIZARD_SITE_DIR")) return;
if(!defined("WIZARD_SITE_PATH")) return;
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";	

set_time_limit(0);

if (!CModule::IncludeModule("highloadblock"))
	return;

if (!WIZARD_INSTALL_DEMO_DATA)
	return;

$HL_ID = $_SESSION["STROY_HBLOCK_COLOR_ID"];
unset($_SESSION["STROY_HBLOCK_COLOR_ID"]);

//adding rows
WizardServices::IncludeServiceLang("references.php", LANGUAGE_ID);

use Bitrix\Highloadblock as HL;
global $USER_FIELD_MANAGER;

if($HL_ID){
	$hldata = HL\HighloadBlockTable::getById($HL_ID)->fetch();
	$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

	$entity_data_class = $hlentity->getDataClass();
	$arProfits = array(
		"LOW_PRICE" => array(
			"IMAGE" => "references_files/iblock/8ba/8ba9e0da09f4695e69844bc857e5af65.png",
			"LINK" => WIZARD_SITE_DIR."price/",
			"DESC" => GetMessage("WZD_REF_PROFIT_LOW_PRICE_DESC")
		),
		"BUILD_HOUSE" => array(
			"IMAGE" => "references_files/iblock/399/399e840e88823cda056b2851ba176e5f.png"
		),
		"100" => array(
			"IMAGE" => "references_files/iblock/2b6/2b6f3c6bba58d71776c30646b470fd56.png"
		),
		"PROJECTS" => array(
			"IMAGE" => "references_files/iblock/d50/d50f1bc858c517cdadea34194568c82d.png",
			"LINK" => WIZARD_SITE_DIR."catalog/"
		),
		"PROFF" => array(
			"IMAGE" => "references_files/iblock/578/578b9b86d1b5f5e68968824ef6a2dd6a.png",
			"LINK" => WIZARD_SITE_DIR."services/obshchestroitelnye-raboty/"
		),
		"VITRAG" => array(
			"IMAGE" => "references_files/iblock/947/94797e904bdd64e7629ff851855de620.png",
			"DESC" => GetMessage("WZD_REF_PROFIT_VITRAG_DESC")
		),
		"ROOM" => array(
			"IMAGE" => "references_files/iblock/682/682b08dbce61fc4d28c613b3b4db5451.png",
			"DESC" => GetMessage("WZD_REF_PROFIT_ROOM_DESC")
		),
		"SALE" => array(
			"IMAGE" => "references_files/iblock/9f5/9f5c3582cadbaeb7731c431b9c3104d3.png",
			"LINK" => WIZARD_SITE_DIR."price/",
			"DESC" => GetMessage("WZD_REF_PROFIT_SALE_DESC")
		),
		"FREE" => array(
			"IMAGE" => "references_files/iblock/263/263ae1499d77868af37d88751f9a06ad.png",
			"DESC" => GetMessage("WZD_REF_PROFIT_FREE_DESC")
		),
		"BASSEIN" => array(
			"IMAGE" => "references_files/iblock/f09/f09936c37115c76ecf7687b9c6bab0a1.png",
			"DESC" => GetMessage("WZD_REF_PROFIT_BASSEIN_DESC")
		),
		"PARKING" => array(
			"IMAGE" => "references_files/iblock/5f2/5f2f25257d7f2153ba28a56b6e9031c8.png",
			"DESC" => GetMessage("WZD_REF_PROFIT_PARKING_DESC")
		),
		"DUSH" => array(
			"IMAGE" => "references_files/iblock/502/5026721a2fe74e70b82daf7001a82df3.png",
			"DESC" => GetMessage("WZD_REF_PROFIT_DUSH_DESC")
		),
		"BES" => array(
			"IMAGE" => "references_files/iblock/53b/53bd6019011fcb82fa7a1d4e70e980a3.png",
			"DESC" => GetMessage("WZD_REF_PROFIT_BES_DESC")
		),
		"TERR" => array(
			"IMAGE" => "references_files/iblock/cb5/cb5232ed33c58334be63ccb689bece9d.png",
			"DESC" => GetMessage("WZD_REF_PROFIT_TERR_DESC")
		),
	);
	$sort = 100;
	foreach($arProfits as $profitName => $arFile){
		$arData = array(
			'UF_NAME' => GetMessage("WZD_REF_PROFIT_".$profitName),
			'UF_FILE' =>
				array (
					'name' => ToLower($profitName).".png",
					'type' => 'image/png',
					'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$arFile["IMAGE"]
				),
			'UF_SORT' => $sort,
			// 'UF_DEF' => ($sort > 100) ? "0" : "1",
			'UF_XML_ID' => ToLower($profitName)
		);
		if($arFile["DESC"]){
			$arData["UF_DESCRIPTION"]=$arFile["DESC"];
		}
		if($arFile["LINK"]){
			$arData["UF_LINK"]=$arFile["LINK"];
		}
		$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$HL_ID, $arData);
		$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$HL_ID, null, $arData);
		$result = $entity_data_class::add($arData);
		$sort += 100;
	}
}
?>