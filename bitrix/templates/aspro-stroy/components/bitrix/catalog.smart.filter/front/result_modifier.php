<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!$arParams["PROP_1"] && !$arParams["PROP_2"] && !$arParams["PROP_3"])
	$arShowProp=array("SIZE", "FILTER_PRICE", "TYPE_BUILDINGS");
else{
	for($i=1;$i<=3;$i++){
		if($arParams["PROP_".$i]){
			$arShowProp[]=$arParams["PROP_".$i];
		}
	}
}
foreach($arResult["ITEMS"] as $key => $arItem)
{
	if(!in_array($arItem["CODE"],$arShowProp)){
		unset($arResult["ITEMS"][$key]);
	}
}