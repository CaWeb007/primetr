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
$tabPropertyCode = array('DESCRIPTION_DESK','DESCRIPTION_SIZETABLE','DESCRIPTION_SURFTYPE','DESCRIPTION_EQUIP','DESCRIPTION_ADDEQUIP');
$tabCount = 0;
foreach ($tabPropertyCode as $code)
    if (!empty($arResult['PROPERTIES'][$code]['VALUE'])) $tabCount++;
$name = ($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])?$arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']:$arResult['NAME'];
?>
<!--<h2 itemprop="name"><//?=$arResult['NAME']?> <span class="orange-badge">Хит продаж</span></h2>-->
<div class="heading-wrapper">
    <h1 itemprop="name" class="mobile-heading"><?= $name ?>
        <?if($arResult['DISPLAY_PROPERTIES']['STIKERS']['DISPLAY_VALUE']):?>
            <?
                if ($arResult['DISPLAY_PROPERTIES']['STIKERS']['VALUE_XML_ID'][0] === 'DROP_PRICE'){
                    $classStick = 'green-badge';
                }else{
                    $classStick = 'orange-badge';
                }
            ?>
            <span class="<?=$classStick?> mobile-hidden"><?=$arResult['DISPLAY_PROPERTIES']['STIKERS']['DISPLAY_VALUE']?></span>
        <?endif?>
    </h1>
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
            <?if($arResult['DISPLAY_PROPERTIES']['STIKERS']['DISPLAY_VALUE']):?>
                <span class="orange-badge desktop-hidden"><?=$arResult['DISPLAY_PROPERTIES']['STIKERS']['DISPLAY_VALUE']?></span>
            <?endif?>
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
                        <?if (!empty($arResult['PROPERTIES']['CREDIT']['VALUE'])
                            && !empty($arResult['PROPERTIES']['CREDIT']['DESCRIPTION'])):?>
                            <a href="<?=$arResult['PROPERTIES']['CREDIT']['VALUE']?>"
                               class="conditions-link mobile-hidden"><?=$arResult['PROPERTIES']['CREDIT']['DESCRIPTION']?></a>
                        <?endif?>
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
                    <?if ($arResult['PROPERTIES']['IN_STOCK']['VALUE']):?>
                        <span class="avail"><i class="fa fa-check" aria-hidden="true"></i> В наличии</span>
                    <?endif?>
                    <span class="desktop-hidden"><a href="#" class="conditions-link red-color">Рассрочка 0-0-12</a></span>
                </div>
            </div>
                <div class="size-wrapper">
                    <? if ($isSizes) { ?>
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
                    <? } ?>

                    <?// if ($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'): ?>
                        <div class="callback" data-event="jqm" data-param-id="33" data-name="callback">
                            <a href="#">Заказать</a>
                        </div>
                    <?// endif; ?>
                </div>
        </div>
        <?if(!empty($arResult['PROPERTIES']['DETAIL_BLOCKS']['VALUE']) || !empty($arResult['PROPERTIES']['WARRANTY']['VALUE'])):?>
            <div class="testimonials">
                <?foreach ($arResult['PROPERTIES']['DETAIL_BLOCKS']['VALUE'] as $value):?>
                    <?switch ($value): case 'Замер':?>
                        <div class="testimonials-item-wrapper">
                            <div class="testimonials-item">
                                <?$APPLICATION->IncludeComponent('bitrix:main.include','',
                                    array(
                                        'PATH' => '/include/detail/measurement.php',
                                        'AREA_FILE_SHOW' => 'file'
                                    ))
                                ?>
                            </div>
                        </div>
                    <?break; case 'Монтаж':?>
                        <div class="testimonials-item-wrapper">
                            <div class="testimonials-item">
                                <img src="<?= SITE_TEMPLATE_PATH ?>/img/wrench1.svg" alt="">
                                Монтаж<?if($arResult['PROPERTIES']['MOUNTING_PRICE']['VALUE']) echo " от ".$arResult['PROPERTIES']['MOUNTING_PRICE']['VALUE']." руб."?>
                                <?if($arResult['PROPERTIES']['MOUNTING_LINK']['VALUE']):?>
                                    <a href="<?=$arResult['PROPERTIES']['MOUNTING_LINK']['VALUE']?>" class="testimonials-item-link"></a>
                                <?endif?>
                            </div>
                        </div>
                    <?break; case 'Доставка':?>
                        <div class="testimonials-item-wrapper">
                            <div class="testimonials-item">
                                <?$APPLICATION->IncludeComponent('bitrix:main.include','',
                                    array(
                                        'PATH' => '/include/detail/delivery.php',
                                        'AREA_FILE_SHOW' => 'file'
                                    ))
                                ?>
                            </div>
                        </div>
                    <?endswitch;?>
                <?endforeach?>
                <?if(!empty($arResult['PROPERTIES']['WARRANTY']['VALUE'])):?>
                    <div class="testimonials-item-wrapper">
                        <div class="testimonials-item">
                            <img src="<?= SITE_TEMPLATE_PATH ?>/img/warranty.svg" alt="">
                            <?= "Гарантия ".$arResult['PROPERTIES']['WARRANTY']['VALUE']?>
                            <a href="/services/remont/" class="testimonials-item-link"></a>
                        </div>
                    </div>
                <?endif?>
            </div>
        <?endif;?>
        <?if(!empty($arResult['SALES'])):?>
            <?foreach ($arResult['SALES'] as $sale):?>
                <div class="badge-action mobile-hidden">
                    <span class="red-color "><?if ($sale['UF_PREFIX']) echo $sale['UF_PREFIX'].":"?></span> <?if($sale['NAME']) echo $sale['NAME']?>
                    <p></p>
                    <?if ($sale['PREVIEW_TEXT']):?>
                        <p class="badge-text" style="display:none;"><?=$sale['PREVIEW_TEXT']?></p>
                        <a class="read-more-badge">Подробнее...</a>
                    <?elseif($sale['DETAIL_PAGE_URL']):?>
                        <a href="<?=$sale['DETAIL_PAGE_URL']?>">Подробнее...</a>
                    <?endif?>
                </div>
                <div class="badge-action desktop-hidden">
                    <span class="red-color "><?if ($sale['UF_PREFIX']) echo $sale['UF_PREFIX']." "?><br><?if($sale['NAME']) echo $sale['NAME']?></span>
                    <p></p>
                    <?if ($sale['PREVIEW_TEXT']):?>
                        <p><?=$sale['PREVIEW_TEXT']?></p>
                    <?elseif ($sale['DETAIL_PAGE_URL']):?>
                        <a href="<?=$sale['DETAIL_PAGE_URL']?>" class="">Подробнее...</a>
                    <?endif?>
                </div>
            <?endforeach?>
        <?endif?>
    </div>
</div>
<? if ($tabCount > 0): ?>
    <div class="accordion-row mobile-hidden">
        <?if($tabCount > 1):?>
        <ul class="nav nav-tabs" role="tablist">
            <? if ($arResult['PROPERTIES']['DESCRIPTION_DESK']['VALUE']): ?>
                <li role="presentation" class="active"><a href="#desk" aria-controls="home" role="tab"
                                                          data-toggle="tab"><h2>Описание и характеристики</h2></a></li>
            <? endif; ?>
            <? if ($arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['VALUE']): ?>
                <li role="presentation"><a href="#sizetable" aria-controls="profile" role="tab" data-toggle="tab"><h2>Цена</h2></a></li>
            <? endif; ?>
            <? if ($arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['VALUE']): ?>
                <li role="presentation"><a href="#surftype" aria-controls="messages" role="tab" data-toggle="tab"><h2>Цвета</h2></a></li>
            <? endif; ?>
            <? if ($arResult['PROPERTIES']['DESCRIPTION_EQUIP']['VALUE']): ?>
                <li role="presentation"><a href="#equip" aria-controls="settings" role="tab" data-toggle="tab"><h2>Комплектация</h2></a>
                </li>
            <? endif; ?>
            <? if ($arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['VALUE']): ?>
                <li role="presentation"><a href="#addequip" aria-controls="settings" role="tab" data-toggle="tab"><h2>Доп
                            комплектация</h2></a></li>
            <? endif; ?>
        </ul>
        <?endif?>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane in active" id="desk">
                <div class="white-bg-wrapper">
                    <?if ($tabCount == 1):?>
                        <h2>Описание и характеристики</h2>
                    <?endif?>
                    <? if ($arResult['PROPERTIES']['DESC_RIGHT_SIDE']['VALUE'] || $arResult['PROPERTIES']['YOUTUBE_RIGHT_SIDE']['VALUE']): ?>
                        <div class="multimedia-wrapper">
                            <?if($arResult['PROPERTIES']['YOUTUBE_RIGHT_SIDE']['VALUE']):?>
                                <?=$arResult['PROPERTIES']['YOUTUBE_RIGHT_SIDE']['~VALUE']['TEXT'];?>
                            <?elseif ($arResult['PROPERTIES']['DESC_RIGHT_SIDE']['IS_IMAGE']):?>
                                <img src="<?=$arResult['PROPERTIES']['DESC_RIGHT_SIDE']['FILE']['SRC']?>" alt="">
                            <?else:?>
                                <div id="video">
                                    <div class="video-control" id="video-play"><i class="fa fa-play-circle"
                                                                                  aria-hidden="true"></i></div>
                                    <div class="video-control" id="video-over"></div>
                                    <video id="myVideo" preload="auto">
                                        <source id='mp4'
                                                src="<?= $arResult['PROPERTIES']['DESC_RIGHT_SIDE']['FILE']['SRC'] ?>"
                                                type='video/mp4'/>
                                    </video>
                                </div>
                                <!--button class="red-color play-button">
                                    Принцип работы секционных ворот (смотреть видео)
                                </button-->
                            <?endif?>
                        </div>
                    <? endif; ?>
                    <div class="text-block">
                        <p><?= $arResult['PROPERTIES']['DESCRIPTION_DESK']['~VALUE']['TEXT'] ?></p>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="sizetable">
                <div class="white-bg-wrapper">
                    <?if ($tabCount == 1):?>
                        <h2>Цена</h2>
                    <?endif?>
                    <? foreach ($arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['VALUE'] as $key => $file): ?>
                        <div class="text-block">
                            <img src="<?= CFile::GetPath($file); ?>" alt="">
                            <div class="text-block"><?=$arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['DESCRIPTION'][$key]?></div>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="surftype">
                <div class="white-bg-wrapper">
                    <?if ($tabCount == 1):?>
                        <h2>Цвета</h2>
                    <?endif?>
                    <div class="text-block">
                        <div class="row accordion-row__row-flex">
                            <? foreach ($arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['VALUE'] as $key => $file): ?>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <img src="<?= CFile::GetPath($file); ?>" alt="">
                                    <div><?=$arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['DESCRIPTION'][$key]?></div>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="equip">
                <div class="white-bg-wrapper">
                    <?if ($tabCount == 1):?>
                        <h2>Комплектация</h2>
                    <?endif?>
                    <div class="text-block">
                        <div class="row accordion-row__row-flex">
                            <? foreach ($arResult['PROPERTIES']['DESCRIPTION_EQUIP']['VALUE'] as $key => $file): ?>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <img src="<?= CFile::GetPath($file); ?>" alt="">
                                    <div><?=$arResult['PROPERTIES']['DESCRIPTION_EQUIP']['DESCRIPTION'][$key]?></div>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="addequip">
                <div class="white-bg-wrapper">
                    <?if ($tabCount == 1):?>
                        <h2>Доп. комплектация</h2>
                    <?endif?>
                    <div class="text-block">
                        <div class="row accordion-row__row-flex">
                            <? foreach ($arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['VALUE'] as $key => $file): ?>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <img src="<?= CFile::GetPath($file); ?>" alt="">
                                    <div><?=$arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['DESCRIPTION'][$key]?></div>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? endif; ?>


</div>
</div>
</div>



<? if ($tabCount > 0): ?>
    <div class="accordion-row desktop-hidden">
        <div class="panel-group" id="accordion">
            <?if ($arResult['PROPERTIES']['DESCRIPTION_DESK']['~VALUE']['TEXT']):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class=""
                               aria-expanded="false">Описание и характеристики <i class="fa fa-angle-down"
                                                                                  aria-hidden="true"></i></a>
                        </h2>
                    </div>
                    <div id="collapseOne" class="in" aria-expanded="false">
                        <div class="panel-body">
                            <div class="text-block">
                                <p><?= $arResult['PROPERTIES']['DESCRIPTION_DESK']['~VALUE']['TEXT'] ?></p>
                            </div>
                            <? if ($arResult['PROPERTIES']['DESC_RIGHT_SIDE']['VALUE'] || $arResult['PROPERTIES']['YOUTUBE_RIGHT_SIDE']['VALUE']): ?>
                                <div class="multimedia-wrapper">
                                    <?if($arResult['PROPERTIES']['YOUTUBE_RIGHT_SIDE']['VALUE']):?>
                                        <?=$arResult['PROPERTIES']['YOUTUBE_RIGHT_SIDE']['~VALUE']['TEXT'];?>
                                    <?elseif ($arResult['PROPERTIES']['DESC_RIGHT_SIDE']['IS_IMAGE']):?>
                                        <img src="<?=$arResult['PROPERTIES']['DESC_RIGHT_SIDE']['FILE']['SRC']?>" alt="">
                                    <?else:?>
                                        <div id="video">
                                            <div class="video-control" id="video-play"><i class="fa fa-play-circle"
                                                                                          aria-hidden="true"></i></div>
                                            <div class="video-control" id="video-over"></div>
                                            <video id="myVideo" preload="auto">
                                                <source id='mp4'
                                                        src="<?= $arResult['PROPERTIES']['DESC_RIGHT_SIDE']['FILE']['SRC'] ?>"
                                                        type='<?= $arResult['PROPERTIES']['DESC_RIGHT_SIDE']['FILE']['CONTENT_TYPE']?>'/>
                                            </video>
                                        </div>
                                        <!--button class="red-color play-button">
                                            Принцип работы секционных ворот (смотреть видео)
                                        </button-->
                                    <?endif?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?endif;?>
            <?if ($arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['VALUE']):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed"
                               aria-expanded="false">Таблица размеров <i class="fa fa-angle-down"
                                                                         aria-hidden="true"></i></a>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false">
                        <div class="panel-body">
                            <div class="white-bg-wrapper">
                                <? foreach ($arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['VALUE'] as $key => $file): ?>
                                    <div class="accordion-row__panel-body__item">
                                        <img style="width: 100%;" src="<?= CFile::GetPath($file); ?>" alt="">
                                        <div class="text-block"><?=$arResult['PROPERTIES']['DESCRIPTION_SIZETABLE']['DESCRIPTION'][$key]?></div>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif;?>
            <?if ($arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['VALUE']):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed"
                               aria-expanded="false">Цвета <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                        </h2>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false">
                        <div class="panel-body">
                            <div class="white-bg-wrapper">
                                <? foreach ($arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['VALUE'] as $key => $file): ?>
                                    <div class="accordion-row__panel-body__item">
                                        <img style="width: 100%;" src="<?= CFile::GetPath($file); ?>" alt="">
                                        <div class="text-block"><?=$arResult['PROPERTIES']['DESCRIPTION_SURFTYPE']['DESCRIPTION'][$key]?></div>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif;?>
            <?if ($arResult['PROPERTIES']['DESCRIPTION_EQUIP']['VALUE']):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" class="collapsed"
                               aria-expanded="false">Комплектация <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                        </h2>
                    </div>
                    <div id="collapseFour" class="panel-collapse collapse collapse" aria-expanded="false">
                        <div class="panel-body">
                            <div class="white-bg-wrapper">
                                <? foreach ($arResult['PROPERTIES']['DESCRIPTION_EQUIP']['VALUE'] as $key => $file): ?>
                                    <div class="accordion-row__panel-body__item">
                                        <img style="width: 100%;" src="<?= CFile::GetPath($file); ?>" alt="">
                                        <div class="text-block"><?=$arResult['PROPERTIES']['DESCRIPTION_EQUIP']['DESCRIPTION'][$key]?></div>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif;?>
            <?if ($arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['VALUE']):?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" class="collapsed"
                               aria-expanded="false">Доп комплектация <i class="fa fa-angle-down"
                                                                         aria-hidden="true"></i></a>
                        </h2>
                    </div>
                    <div id="collapseFive" class="panel-collapse collapse" aria-expanded="false">
                        <div class="panel-body">
                            <div class="white-bg-wrapper">
                                <? foreach ($arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['VALUE'] as $key => $file): ?>
                                    <div class="accordion-row__panel-body__item">
                                        <img style="width: 100%;" src="<?= CFile::GetPath($file); ?>" alt="">
                                        <div class="text-block"><?=$arResult['PROPERTIES']['DESCRIPTION_ADDEQUIP']['DESCRIPTION'][$key]?></div>
                                    </div>
                                <? endforeach; ?>
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
        <h2 class="red-color">Наши преимущества:</h2>
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
            <span>Для расчета стоимости позвоните по тел.:</span>
            <a id="reviews_desktop" href="tel:+73952280700"><i class="fa fa-phone" aria-hidden="true"></i>+7 (3952)
                280-700</a>
        </div>
    </div>
</div>
<div class="call-us-block desktop-hidden">
    <div class="white-border">
        <span>Для расчета стоимости позвоните по тел.:</span>
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
            <div class="ya-share2" data-services="vkontakte,telegram,viber,whatsapp,odnoklassniki"></div>
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
