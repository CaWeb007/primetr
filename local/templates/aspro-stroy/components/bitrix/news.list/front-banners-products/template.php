<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?if($arResult['ITEMS']):?>
    <div class="products-slider">
        <?foreach($arResult['ITEMS'] as $i => $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="slider-item" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
                <div class="slider-image">
                    <img src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>">
                    <?if ($arItem['PROPERTIES']['LINK']['VALUE']):?>
                        <a href="<?=$arItem['PROPERTIES']['LINK']['VALUE']?>"></a>
                    <?endif;?>
                </div>
                <?if (!empty($arItem['PROPERTIES']['SALE']['VALUE'])):?>
                    <div class="discount">
		                <span class="percents">
		                    <?=$arItem['PROPERTIES']['SALE']['VALUE'].'%'?>
		                </span>
                        Скидка
                    </div>
                <?endif;?>

                <!--div class="details-row">
                    <div class="rating">
                        <img src="<?//=$this->GetFolder()?>/images/star.svg" alt="">
                        5
                    </div>
                    <div class="reviews">
                        <img src="<?//=$this->GetFolder()?>/images/comm.svg" alt="">
                        <a href="#">
                            6 отзывов
                        </a>
                    </div>
                </div-->
                <h3 class="product-heading">
                    <?if ($arItem['PROPERTIES']['LINK']['VALUE']):?>
                        <a href="<?=$arItem['PROPERTIES']['LINK']['VALUE']?>"><?=$arItem['NAME']?></a>
                    <?else:?>
                        <?=$arItem['NAME']?>
                    <?endif;?>
                </h3>
                <?if (!empty($arItem['PROPERTIES']['PRICE']['VALUE'])
                    || !empty($arItem['PROPERTIES']['OLD_PRICE']['VALUE'])):?>
                    <div class="order-block-front">
                        <?if (!empty($arItem['PROPERTIES']['PRICE']['VALUE'])
                            || !empty($arItem['PROPERTIES']['OLD_PRICE']['VALUE'])):?>
                            <div class="price">
                                <?if (!empty($arItem['PROPERTIES']['OLD_PRICE']['VALUE'])):?>
                                    <div class="last-price">
                                        <?=$arItem['PROPERTIES']['OLD_PRICE']['VALUE'].' руб.'?>
                                    </div>
                                <?endif;?>
                                <?if (!empty($arItem['PROPERTIES']['PRICE']['VALUE'])):?>
                                    <div class="new-price">
                                        <?=$arItem['PROPERTIES']['PRICE']['VALUE'].' руб.'?>
                                    </div>
                                <?endif;?>
                            </div>
                        <?endif;?>
                        <div class="callback" data-event="jqm" data-param-id="33" data-name="callback" data-autoload-product="<?=$arItem['NAME'].' (Акция)'?>">
                            <a class="order-btn" href="#">ЗАКАЗАТЬ</a>
                        </div>
                    </div>
                <?endif;?>
        </div>
        <?endforeach;?>
    </div>
<?endif;?>