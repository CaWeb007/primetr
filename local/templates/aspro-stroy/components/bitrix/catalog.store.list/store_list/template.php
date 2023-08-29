<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->addExternalJs($this->GetFolder().'/js/jquery.eymaps.min.js');
$this->addExternalJs('https://api-maps.yandex.ru/2.1/?lang=ru_RU');
?>
<div class="caweb-store-list-container">
    <div class="store-list-column">
        <div class="store-list-wrap" id="store_list_wrap">
            <?$first = true;?>
            <?foreach($arResult['STORES'] as $arStore):?>
                <div
                        class="store-wrap<?if($first) echo ' selected';?>"
                        id="store_wrap"
                        data-gpsn="<?=$arStore['GPS_N']?>"
                        data-gpss="<?=$arStore['GPS_S']?>"
                        data-storeId="<?=$arStore['ID']?>">
                    <div class="store-addr"><?=$arStore['ADDRESS']?></div>
                    <div class="store-phone"><a href="tel:<?=$arStore['SCHEDULE']?>"><?=$arStore['PHONE']?></a></div>
                </div>
                <?$first = false;?>
            <?endforeach;?>
        </div>
    </div>
    <div class="map-column">
        <div class="map-wrap">
            <div class="map" id="map"></div>
        </div>
    </div>
</div>
<script>
    CawebMapStore.init({
        mapId: "map",
        triggerWrapId: "store_list_wrap",
        triggerItemId: "store_wrap",
        stores: <?=Bitrix\Main\Web\Json::encode($arResult['STORES'])?>
    })
</script>
