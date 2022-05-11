<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
// id highload-инфоблока
const MY_HL_BLOCK_ID = 2;
$entity_data_class = GetEntityDataClass(MY_HL_BLOCK_ID);
$rsData = $entity_data_class::getList(array(
    'order' => array('UF_MESSAGE_ID' => 'ASC'),
    'select' => array('*'),
    'filter' => array('!UF_MESSAGE_ID' => false, 'UF_ELEMENT_ID' => $arResult['ID'])
));
$sum = 0;
$count = 0;
$avg = 0;
while ($el = $rsData->fetch()) {
    $sum += $el['UF_RATING'];
    $count += 1;
}
$avg = $sum / $count;
?>
<!--<h2 itemprop="name"><//?=$arResult['NAME']?> <span class="orange-badge">Хит продаж</span></h2>-->
<div class="heading-wrapper">
    <h2 itemprop="name" class="mobile-heading"><?= $arResult['NAME'] ?> <span class="orange-badge mobile-hidden">Хит продаж</span>
    </h2>
    <div class="made-logo desktop-hidden">
        <img src="<?= CFile::GetPath($arResult['PROPERTIES']['PRODUCT_LOGO']['VALUE']); ?>" alt="">
    </div>
</div>
<div class="product-row">
    <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating" id="rate"
         class="col-lg-12 col-xs-12 rating desktop-hidden mobile-top-rate">
        <? if (is_nan($avg)): ?>
            <? for ($j = 1; $j <= 5; $j++): ?>
                <i itemprop="ratingValue" class="fa fa-star" aria-hidden="true"></i>
            <? endfor; ?>
        <? endif; ?>
        <? for ($i = 1; $i <= round($avg); $i++): ?>
            <i itemprop="ratingValue" class="fa fa-star rate" aria-hidden="true"></i>
        <? endfor; ?>
        <? for ($j = round($avg) + 1; $j <= 5; $j++): ?>
            <i itemprop="ratingValue" class="fa fa-star" aria-hidden="true"></i>
        <? endfor; ?>
        <a href="#reviews" class="reviews-link">
            <?= $count ?>
            <? if ($count % 10 === 1): ?>
                отзыв
            <? elseif (($count % 10 === 2) || ($count % 10 === 3) || ($count % 10 === 4)): ?>
                отзыва
            <? elseif (($count % 10 >= 5) || ($count % 10 === 0)): ?>
                отзывов
            <? endif; ?>
        </a>
        <a href="#" class="share-icon" data-toggle="modal" data-target="#shareModal"><i class="fa fa-share-alt"
                                                                                        aria-hidden="true"></i> <span
                    class="link" style="display: none;">Ссылка на страницу товара</span></a>
    </div>
    <div class="mobile-slider-container">
        <div class="gallery-block col-lg-7 col-xs-7">
            <span class="orange-badge desktop-hidden">Хит продаж</span>
            <div id="carouselImage" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner" role="listbox">
                    <? $countAll = count($arResult['GALLERY']); ?>
                    <div class="item active" data-toggle="modal" data-target="#myModal">
                        <img src="<?= $arResult['GALLERY'][0]['PREVIEW']['src'] ?>" alt="...">
                    </div>
                    <? for ($i = 1; $i < $countAll; $i++): ?>
                        <div class="item" data-toggle="modal" data-target="#myModal">
                            <img src="<?= $arResult['GALLERY'][$i]['PREVIEW']['src'] ?>" alt="...">
                        </div>
                    <? endfor; ?>
                    <? if ($arResult['PROPERTIES']['SLIDER_VIDEO']['VALUE']): ?>
                        <div class="item" data-toggle="modal" data-target="#myModal">
                            <video id="myVideo1" preload="auto" controls>
                                <source id='mp4'
                                        src="<?= CFile::GetPath($arResult['PROPERTIES']['SLIDER_VIDEO']['VALUE']) ?>"
                                        type='video/mp4'/>
                            </video>
                        </div>
                    <? endif; ?>
                </div>
                <!-- Indicators -->
                <ol class="carousel-indicators list-line">
                    <li data-target="#carouselImage" data-slide-to="0" class="active">
                        <img src="<?= $arResult['GALLERY'][0]['PREVIEW']['src'] ?>" alt="...">
                    </li>
                    <? $countAll = count($arResult['GALLERY']); ?>
                    <? if ($countAll > 4) {
                        $j = 4;
                    } else {
                        $j = $countAll;
                    }
                    ?>
                    <? for ($i = 1; $i < $j; $i++): ?>
                        <li data-target="#carouselImage" data-slide-to="<?= $i ?>">
                            <img src="<?= $arResult['GALLERY'][$i]['PREVIEW']['src'] ?>" alt="...">
                        </li>
                    <? endfor; ?>
                    <? if ($arResult['PROPERTIES']['SLIDER_VIDEO']['VALUE']): ?>
                        <li data-target="#carouselImage" data-slide-to="<?= $countAll - 1 ?>">
                            <video id="myVideo1" preload="auto">
                                <source id='mp4'
                                        src="<?= CFile::GetPath($arResult['PROPERTIES']['SLIDER_VIDEO']['VALUE']) ?>"
                                        type='video/mp4'/>
                            </video>
                            <div class="demo-play"><i class="fa fa-play-circle" aria-hidden="true"></i></div>
                        </li>
                    <? endif; ?>
                </ol>
                <!-- Controls -->
                <? if ($j > 1): ?>
                    <a class="left carousel-control" href="#carouselImage" role="button" data-slide="prev">
                        <img src="<?= SITE_TEMPLATE_PATH ?>/img/arrow.svg" alt="">
                    </a>
                    <a class="right carousel-control" href="#carouselImage" role="button" data-slide="next">
                        <img src="<?= SITE_TEMPLATE_PATH ?>/img/arrow.svg" alt="">
                    </a>
                <? endif; ?>
            </div>
        </div>
    </div>
    <div class="price-block col-lg-5 col-xs-5 col-12">
        <div class="top-row mobile-hidden">
            <div class="rating">
                <? if (is_nan($avg)): ?>
                    <? for ($j = 1; $j <= 5; $j++): ?>
                        <i itemprop="ratingValue" class="fa fa-star" aria-hidden="true"></i>
                    <? endfor; ?>
                <? endif; ?>
                <? for ($i = 1; $i <= round($avg); $i++): ?>
                    <i itemprop="ratingValue" class="fa fa-star rate" aria-hidden="true"></i>
                <? endfor; ?>
                <? for ($j = round($avg) + 1; $j <= 5; $j++): ?>
                    <i itemprop="ratingValue" class="fa fa-star" aria-hidden="true"></i>
                <? endfor; ?>
                <a href="#reviews_desktop" class="reviews-link">
                    <?= $count ?>
                    <? if ($count % 10 === 1): ?>
                        отзыв
                    <? elseif (($count % 10 === 2) || ($count % 10 === 3) || ($count % 10 === 4)): ?>
                        отзыва
                    <? elseif (($count % 10 >= 5) || ($count % 10 === 0)): ?>
                        отзывов
                    <? endif; ?>
                </a>
            </div>
            <? if ($arResult['PROPERTIES']['PRODUCT_LOGO']): ?>
                <div class="made-logo">
                    <img src="<?= CFile::GetPath($arResult['PROPERTIES']['PRODUCT_LOGO']['VALUE']); ?>" alt="">
                </div>
            <? endif; ?>
        </div>
        <?
        $isSizes = ($arResult['PROPERTIES']['SIZES']['VALUE'] ? true : false);
        $isPrices = (strlen($arResult['DISPLAY_PROPERTIES']['PRICE']['VALUE']) ? true : false);
        ?>
        <div class="price-select-block">
            <div class="top-row top-row-margin">
                <? if ($isPrices): ?>
                    <div class="price-row">
                        <div class="price-before">
                            <?= $arResult['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE'] ?>
                        </div>
                        <div class="price-now">
                            <?= $arResult['DISPLAY_PROPERTIES']['PRICE']['VALUE'] ?>
                        </div>
                        <a href="#" class="conditions-link mobile-hidden">Кредит 0-0-6</a>
                    </div>
                <? endif; ?>
                <div class="avail-row">
                    <a href="#" class="share-icon mobile-hidden" data-toggle="modal" data-target="#shareModal"><i
                                class="fa fa-share-alt" aria-hidden="true"></i> <span class="link"
                                                                                      style="display: none;">Ссылка на страницу товара</span></a>
                    <? if ($arParams['USE_SHARE'] == 'Y'): ?>
                        <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"
                                charset="utf-8"></script>
                        <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"
                                async="async"></script>
                    <? endif; ?>
                    <span class="avail"><i class="fa fa-check" aria-hidden="true"></i> В наличии</span>
                    <span class="desktop-hidden"><a href="#" class="conditions-link red-color">Кредит 0-0-6</a></span>
                </div>
            </div>
            <? if ($isSizes) { ?>
                <div class="size-wrapper">
                    <form>
                        <div class="select" data-state="">
                            <div class="select-title">Выберите размер(м)</div>
                            <div class="select-content">
                                <input id="Select-0" class="select-input" type="radio" name="singleSelect" checked/>
                                <label for="Select-0" class="select-label">Выберите размер(м)</label>
                                <? for ($i = 1; $i < count($arResult['PROPERTIES']['SIZES_PRICE_BEFORE']['VALUE']); $i++): ?>
                                    <input id="Select-<?= $i ?>" class="select-input" type="radio" name="singleSelect"/>
                                    <label data-valuea="<?= $arResult['PROPERTIES']['SIZES_PRICE_BEFORE']['VALUE'][$i] ?>"
                                           data-valueb="<?= $arResult['PROPERTIES']['SIZES_PRICE_AFTER']['VALUE'][$i] ?>"
                                           for="Select-<?= $i ?>"
                                           class="select-label"><?= $arResult['PROPERTIES']['SIZES']['VALUE'][$i] ?></label>
                                <? endfor; ?>
                            </div>
                        </div>
                    </form>
                    <? if ($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'): ?>
                        <div class="callback" data-event="jqm" data-param-id="17" data-name="callback">
                            <a href="#">Заказать</a>
                        </div>
                    <? endif; ?>
                </div>
            <? } ?>
        </div>
        <div class="testimonials">
            <div class="testimonials-item-wrapper">
                <div class="testimonials-item">
                    <img src="<?= SITE_TEMPLATE_PATH ?>/img/ruler1.svg" alt="">Замер по г.Иркутск - <span
                            class="red-color">бесплатный</span>
                    <div class="tooltiptext">
                        <div class="tooltiptext-wrapper" style="padding:20px;">
                            <p><i class="fa fa-angle-right red-color" aria-hidden="true"></i>Доставим готовые изделия в
                                любой район г.Иркутска и области</p>
                            <p><i class="fa fa-angle-right red-color" aria-hidden="true"></i>Работаем с проверенеными
                                транспортными компаниями. Так же возможен самовывоз</p>
                            <p><i class="fa fa-angle-right red-color" aria-hidden="true"></i>После комплектации заказа
                                мы свяжемся с вами, уточним время и дату доставки</p>
                            <span><a href="#" class="red-color">Читать полностью</a></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonials-item-wrapper">
                <div class="testimonials-item">
                    <img src="<?= SITE_TEMPLATE_PATH ?>/img/wrench1.svg" alt="">
                    Монтаж от 4000 руб.
                </div>
            </div>
            <div class="testimonials-item-wrapper">
                <div class="testimonials-item">
                    <img src="<?= SITE_TEMPLATE_PATH ?>/img/truck.svg" alt=""> Доставка
                    <div class="tooltiptext">
                        <div class="tooltiptext-wrapper" style="padding:20px;">
                            <p><i class="fa fa-angle-right red-color" aria-hidden="true"></i>Доставим готовые изделия в
                                любой район г.Иркутска и области</p>
                            <p><i class="fa fa-angle-right red-color" aria-hidden="true"></i>Работаем с проверенеными
                                транспортными компаниями. Так же возможен самовывоз</p>
                            <p><i class="fa fa-angle-right red-color" aria-hidden="true"></i>После комплектации заказа
                                мы свяжемся с вами, уточним время и дату доставки</p>
                            <span><a href="#" class="red-color">Читать полностью</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="badge-action mobile-hidden">
            <span class="red-color ">Акция:</span> Ворота размером 4x2м и 4,5x2м за 39000 рублей + монтаж в подарок!
            <p></p>
            <p class="badge-text" style="display:none;">Полотно секционных ворот представляет собой два соединенных друг
                размещаясь в пространстве под потолком, с другом листа стали, заполненных теплоизоляционным материалом -
                вспененным полиуретаном. Направляющие представляют собой стальные профили с кронштейнами, которые
                крепятся к проему еоторым осуществляется движение полотна...</p> <a
                    class="read-more-badge">Подробнее...</a></div>
        <div class="badge-action desktop-hidden">
            <span class="red-color ">Акция <br> Ворота размером 4x2м и 4,5x2м за 39000 рублей + монтаж в подарок! </span>
            <p></p>
            <p>Полотно секционных ворот представляет собой два соединенных друг размещаясь в пространстве под потолком,
                с другом листа стали, заполненных теплоизоляционным материалом - вспененным полиуретаном. Направляющие
                представляют собой стальные профили с кронштейнами, которые крепятся к проему еоторым осуществляется
                движение полотна...</p>
        </div>
    </div>
</div>
<? if ($arResult['PROPERTIES']['DESCRIPTION_DESK']['~VALUE']['TEXT'] ||
        $arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['~VALUE']['TEXT'] ||
        $arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['~VALUE']['TEXT'] ||
        $arResult['PROPERTIES']['DESCRIPTION_EQUIP']['~VALUE']['TEXT'] ||
        $arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['~VALUE']['TEXT']): ?>
    <div class="accordion-row mobile-hidden">
        <ul class="nav nav-tabs" role="tablist">
            <? if ($arResult['PROPERTIES']['DESCRIPTION_DESK']['~VALUE']['TEXT']): ?>
                <li role="presentation" class="active"><a href="#desk" aria-controls="home" role="tab"
                                                          data-toggle="tab">Описание и характеристики</a></li>
            <? endif; ?>
            <? if ($arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['~VALUE']['TEXT']): ?>
                <li role="presentation"><a href="#sizetable" aria-controls="profile" role="tab" data-toggle="tab">Таблица
                        размеров</a></li>
            <? endif; ?>
            <? if ($arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['~VALUE']['TEXT']): ?>
                <li role="presentation"><a href="#surftype" aria-controls="messages" role="tab" data-toggle="tab">Тип
                        поверхности</a></li>
            <? endif; ?>
            <? if ($arResult['PROPERTIES']['DESCRIPTION_EQUIP']['~VALUE']['TEXT']): ?>
                <li role="presentation"><a href="#equip" aria-controls="settings" role="tab" data-toggle="tab">Комплектация</a>
                </li>
            <? endif; ?>
            <? if ($arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['~VALUE']['TEXT']): ?>
                <li role="presentation"><a href="#addequip" aria-controls="settings" role="tab" data-toggle="tab">Доп
                        комплектация</a></li>
            <? endif; ?>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane in active" id="desk">
                <h2>Описание и характеристики</h2>
                <div class="white-bg-wrapper">
                    <? if ($arResult['PROPERTIES']['DESC_RIGHT_SIDE']['VALUE']): ?>
                        <div class="multimedia-wrapper">
                            <div id="video">
                                <div class="video-control" id="video-play"><i class="fa fa-play-circle"
                                                                              aria-hidden="true"></i></div>
                                <div class="video-control" id="video-over"></div>
                                <video id="myVideo" preload="auto">
                                    <source id='mp4'
                                            src="<?= CFile::GetPath($arResult['PROPERTIES']['DESC_RIGHT_SIDE']['VALUE']) ?>"
                                            type='video/mp4'/>
                                </video>
                            </div>
                            <button class="red-color play-button">
                                Принцип работы секционных ворот (смотреть видео)
                            </button>
                        </div>
                    <? endif; ?>
                    <div class="text-block">
                        <p><?= $arResult['PROPERTIES']['DESCRIPTION_DESK']['~VALUE']['TEXT'] ?></p>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="sizetable">
                <h2>Таблица размеров</h2>
                <div class="white-bg-wrapper">
                    <? foreach ($arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['VALUE'] as $file): ?>
                        <img src="<?= CFile::GetPath($file); ?>" alt="">
                    <? endforeach; ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="surftype">
                <h2>Тип поверхности</h2>
                <div class="white-bg-wrapper">
                    <p><?= $arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['~VALUE']['TEXT'] ?></p>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="equip">
                <h2>Комплектация</h2>
                <div class="white-bg-wrapper">
                    <?= $arResult['PROPERTIES']['DESCRIPTION_EQUIP']['~VALUE']['TEXT'] ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="addequip">
                <h2>Доп. комплектация</h2>
                <div class="white-bg-wrapper">
                    <?= $arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['~VALUE']['TEXT'] ?>
                </div>
            </div>
        </div>
    </div>
<? endif; ?>
</div>
</div>
</div>
<? if ($arResult['PROPERTIES']['DESCRIPTION_DESK']['~VALUE']['TEXT'] ||
    $arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['~VALUE']['TEXT'] ||
    $arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['~VALUE']['TEXT'] ||
    $arResult['PROPERTIES']['DESCRIPTION_EQUIP']['~VALUE']['TEXT'] ||
    $arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['~VALUE']['TEXT']): ?>
    <div class="accordion-row desktop-hidden">
        <div class="panel-group" id="accordion">
            <?if ($arResult['DETAIL_TEXT']):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="collapsed"
                               aria-expanded="false">Описание и характеристики <i class="fa fa-angle-down"
                                                                                  aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false">
                        <div class="panel-body">
                            <p><?= $arResult['PROPERTIES']['DESCRIPTION_DESK']['~VALUE']['TEXT'] ?></p>
                        </div>
                    </div>
                </div>
            <?endif;?>
            <?if ($arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['~VALUE']['TEXT']):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed"
                               aria-expanded="false">Таблица размеров <i class="fa fa-angle-down"
                                                                         aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false">
                        <div class="panel-body">
                            <div class="white-bg-wrapper">
                                <? foreach ($arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['VALUE'] as $file): ?>
                                    <img style="width: 100%;" src="<?= CFile::GetPath($file); ?>" alt="">
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif;?>
            <?if ($arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['~VALUE']['TEXT']):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed"
                               aria-expanded="false">Тип поверхности <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false">
                        <div class="panel-body">
                            <div class="text-block">
                                <p><?= $arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['~VALUE']['TEXT'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif;?>
            <?if ($arResult['PROPERTIES']['DESCRIPTION_EQUIP']['~VALUE']['TEXT']):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" class="collapsed"
                               aria-expanded="false">Комплектация <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="collapseFour" class="panel-collapse collapse collapse" aria-expanded="false">
                        <div class="panel-body">
                            <div class="text-block">
                                <p><?= $arResult['PROPERTIES']['DESCRIPTION_EQUIP']['~VALUE']['TEXT'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif;?>
            <?if ($arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['~VALUE']['TEXT']):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" class="collapsed"
                               aria-expanded="false">Доп комплектация <i class="fa fa-angle-down"
                                                                         aria-hidden="true"></i></a>
                        </h4>
                    </div>
                    <div id="collapseFive" class="panel-collapse collapse" aria-expanded="false">
                        <div class="panel-body">
                            <div class="text-block">
                                <p><?= $arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['~VALUE']['TEXT'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif;?>
        </div>
    </div>
<? endif; ?>
<div class="container">
    <div class="gates-testimonials">
        <h2 class="red-color">Преимущества секционных ворот:</h2>
        <br>
        <? /*tizers block start*/ ?>
        <? $useBrands = ('Y' == $arParams['BRAND_USE']);
        if ($useBrands) {
            ?>
            <? $APPLICATION->IncludeComponent("bitrix:catalog.brandblock", "stroy", array(
                "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                "ELEMENT_ID" => $arResult['ID'],
                "ELEMENT_CODE" => "",
                "PROP_CODE" => $arParams["BRAND_PROP_CODE"],
                "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                "CACHE_TIME" => $arParams['CACHE_TIME'],
                "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                "ELEMENT_COUNT" => 5,
                "WIDTH" => "60",
                "WIDTH_SMALL" => "60",
                "HEIGHT" => "60",
                "HEIGHT_SMALL" => "60",
            ),
                $component,
                array("HIDE_ICONS" => "Y")
            ); ?>
        <? } ?>
        <? /*tizers block end*/ ?>
    </div>
</div>
<div class="container mobile-hidden">
    <div class="call-us-block">
        <div class="white-border">
            <span>Для расчета стоимости секционных ворот позвоните по тел.:</span>
            <a id="reviews_desktop" href="tel:+73952280700"><i class="fa fa-phone" aria-hidden="true"></i>+7 (3952)
                280-700</a>
        </div>
    </div>
</div>
<div class="call-us-block desktop-hidden">
    <div class="white-border">
        <span>Для расчета стоимости секционных ворот позвоните по тел.:</span>
        <a href="tel:+73952280700"><i class="fa fa-phone" aria-hidden="true"></i>+7 (3952) 280-700</a>
    </div>
</div>
<div class="container">
    <? // docs files?>
    <? if ($arResult['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE']): ?>
        <div class="docs-block">
            <h2><?= (strlen($arParams['T_DOCS']) ? $arParams['T_DOCS'] : GetMessage('T_DOCS')) ?></h2>
            <div class="docs-row">
                <? foreach ($arResult['PROPERTIES']['DOCUMENTS']['VALUE'] as $docID): ?>
                    <? $arItem = CStroy::get_file_info($docID); ?>
                    <div class="docs-item">
                        <?
                        $fileName = substr($arItem['ORIGINAL_NAME'], 0, strrpos($arItem['ORIGINAL_NAME'], '.'));
                        $fileTitle = (strlen($arItem['DESCRIPTION']) ? $arItem['DESCRIPTION'] : $fileName);
                        switch ($arItem['TYPE']) {
                            case "pdf":
                                $imgsrc = SITE_TEMPLATE_PATH . '/images/docs/pdf.png';
                                break;
                            case "xls":
                                $imgsrc = SITE_TEMPLATE_PATH . '/images/docs/xls.png';
                                break;
                            case "doc":
                                $imgsrc = SITE_TEMPLATE_PATH . '/images/docs/doc.png';
                                break;
                            case "jpg":
                                $imgsrc = SITE_TEMPLATE_PATH . '/images/docs/jpg.png';
                                break;
                            case "png":
                                $imgsrc = SITE_TEMPLATE_PATH . '/images/docs/png.png';
                                break;
                            case "ppt":
                                $imgsrc = SITE_TEMPLATE_PATH . '/images/docs/ppt.png';
                                break;
                            case "tif":
                                $imgsrc = SITE_TEMPLATE_PATH . '/images/docs/tif.png';
                                break;
                            case "txt":
                                $imgsrc = SITE_TEMPLATE_PATH . '/images/docs/txt.png';
                                break;
                        }
                        ?>
                        <img src="<?= $imgsrc ?>" alt="">
                        <a href="<?= $arItem['SRC'] ?>" target="_blank" title="<?= $fileTitle ?>"
                           class="name-doc"><?= $fileTitle ?></a>
                        <span class="size-doc"><?= GetMessage('CT_NAME_SIZE') ?>: <?= CStroy::filesize_format($arItem['FILE_SIZE']); ?></span>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    <? endif; ?>
</div>
<div class="modal share-modal fade" id="shareModal" role="dialog">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2>Поделиться ссылкой:</h2>
            <div class="ya-share2" data-services="vkontakte,facebook,twitter,viber,whatsapp,odnoklassniki,moimir"></div>
        </div>
    </div>
</div>
<script>
    //change html inner by select option value
    // select option
    const selectSingle = document.querySelector('.select');
    const selectSingle_title = selectSingle.querySelector('.select-title');
    const selectSingle_labels = selectSingle.querySelectorAll('.select-label');
    // Toggle menu
    selectSingle_title.addEventListener('click', () => {
        if ('active' === selectSingle.getAttribute('data-state')) {
            selectSingle.setAttribute('data-state', '');
        } else {
            selectSingle.setAttribute('data-state', 'active');
        }
    });
    // Close when click to option
    for (let i = 0; i < selectSingle_labels.length; i++) {
        selectSingle_labels[i].addEventListener('click', (evt) => {
            selectSingle_title.textContent = evt.target.textContent;
            selectSingle.setAttribute('data-state', '');
        });
    }
    //change html inner by select option value
    var changePrice = function () {
        var select = $('.select-label');
        var displayPrice = $('.price-now');
        var displayPriceBefore = $('.price-before');
        select.click(function () {
            var before = $(this).attr('data-valuea');
            var now = $(this).attr('data-valueb');
            displayPrice.text(now);
            displayPriceBefore.text(before);
        });
    }
    changePrice();
</script>
