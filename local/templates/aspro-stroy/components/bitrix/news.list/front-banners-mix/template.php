<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?if($arResult['ITEMS']):?>
    <div class="main-slider">
        <?foreach($arResult['ITEMS'] as $i => $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            if (!empty($arItem['PROPERTIES']['LINK']['VALUE']))
                $link = $arItem['PROPERTIES']['LINK']['VALUE'];
                if ($arItem['DISPLAY_PROPERTIES']['MARKER_ORD']['VALUE'])
                    $link = \Caweb\Main\Tools::getInstance()->getMarkerOrdUri($arItem['DISPLAY_PROPERTIES']['MARKER_ORD']['VALUE'] , $link);
            else
                $link = 'javascript:void(0)';
            ?>
            <a href="<?=$link?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
                <img src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>">
            </a>
            <?if ($link && $arItem['DISPLAY_PROPERTIES']['MARKER_ORD']['VALUE']):?>
                <div class="ord-link">
                    Реклама
                    <i class="fa fa-angle-right"></i>
                    <input type="text" class="ord-link-href" value="<?=$link?>">
                </div>
            <?endif?>
        <?endforeach;?>
    </div>
<?endif;?>