					<?CStroy::checkRestartBuffer();?>
                    <?IncludeTemplateLangFile(__FILE__);?>
                    <?if(!$isIndex):?>
                                <?if(!$isMenu):?>
                                    </div><?// class=col-md-12 col-sm-12 col-xs-12 content-md?>
                                <?elseif($isMenu && $arTheme["SIDE_MENU"]["VALUE"] == "LEFT"):?>
                                    </div><?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
                                <?elseif($isMenu && $arTheme["SIDE_MENU"]["VALUE"] == "RIGHT"):?>
                                    </div><?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
                                    <?if($APPLICATION->GetProperty("MENU")=="Y"){?>
                                        <div class="col-md-3 col-sm-3 col-xs-4 right-menu-md">
                                            <?$APPLICATION->IncludeComponent("bitrix:menu", "left", array(
                                                "ROOT_MENU_TYPE" => "left",
                                                "MENU_CACHE_TYPE" => "A",
                                                "MENU_CACHE_TIME" => "3600",
                                                "MENU_CACHE_USE_GROUPS" => "Y",
                                                "MENU_CACHE_GET_VARS" => array(
                                                ),
                                                "MAX_LEVEL" => "4",
                                                "CHILD_MENU_TYPE" => "subleft",
                                                "USE_EXT" => "Y",
                                                "DELAY" => "N",
                                                "ALLOW_MULTI_SELECT" => "Y"
                                                ),
                                                false
                                            );?>
                                            <div class="sidearea">
                                                <?$APPLICATION->ShowViewContent('under_sidebar_content');?>
                                                <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/under_sidebar.php"), false);?>
                                            </div>
                                        </div>
                                    <?}?>
                                <?endif;?>
                        <?if(!$isContacts):?>
                            </div><?// class="maxwidth-theme?>
                        </div><?// class=row?>
                        <?endif;?>
                    <?endif;?>
                </div><?// class=container?>
                <?if($isIndex):?>
                    <?=$indexEpilog; // buffered from indexblocks.php?>
                <?endif;?>
                <?if($isPrices):?>
                    <?@include(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'/priceblocks.php'));?>
                    <?=$priceProlog; // buffered from priceblocks.php?>
                <?endif;?>
            </div><?// class=main?>
        </div><?// class=body?>

        <footer id="footer">
            <div class="container">
                <div class="row">
                    <div class="maxwidth-theme">
                        <div class="col-md-3">
                            <div class="copy">
                                <?$APPLICATION->IncludeFile(SITE_DIR."include/copy.php", Array(), Array(
                                        "MODE" => "php",
                                        "NAME" => "Copyright",
                                    )
                                );?>
                            </div>
                            <div class="social">
                                        <?$APPLICATION->IncludeComponent("aspro:social.info.stroy", "", array(
                                            "CACHE_TYPE" => "A",
                                            "CACHE_TIME" => "3600000",
                                            "CACHE_GROUPS" => "N"
                                            ),
                                            false
                                        );?>
                            </div>
                            <div id="bx-composite-banner"></div>
							<?if($APPLICATION->GetCurPage() == '/eeeeerrr/'):?>
							<div class="doorhan_calc_link">
								<a href="/calculator_doorhan">
									<img src="/images/doorhan_calc_banner.png" alt="" title="" />
								</a>
							</div>
							<?endif;?>
                        </div>
                        <div class="col-md-9 col-sm-12">
                            <div class="row">
                                <div class="col-md-9 col-sm-9">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4">
                                            <?$APPLICATION->IncludeComponent(
                                                "bitrix:menu", 
                                                "bottom", 
                                                array(
                                                    "ROOT_MENU_TYPE" => "bottom1",
                                                    "MENU_CACHE_TYPE" => "A",
                                                    "MENU_CACHE_TIME" => "3600000",
                                                    "MENU_CACHE_USE_GROUPS" => "N",
                                                    "MENU_CACHE_GET_VARS" => array(
                                                    ),
                                                    "MAX_LEVEL" => "2",
                                                    "CHILD_MENU_TYPE" => "left",
                                                    "USE_EXT" => "Y",
                                                    "DELAY" => "N",
                                                    "ALLOW_MULTI_SELECT" => "Y",
                                                    "COMPONENT_TEMPLATE" => "bottom"
                                                ),
                                                false
                                            );?>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <?$APPLICATION->IncludeComponent(
                                                "bitrix:menu", 
                                                "bottom", 
                                                array(
                                                    "ROOT_MENU_TYPE" => "bottom2",
                                                    "MENU_CACHE_TYPE" => "A",
                                                    "MENU_CACHE_TIME" => "3600000",
                                                    "MENU_CACHE_USE_GROUPS" => "N",
                                                    "MENU_CACHE_GET_VARS" => array(
                                                    ),
                                                    "MAX_LEVEL" => "2",
                                                    "CHILD_MENU_TYPE" => "left",
                                                    "USE_EXT" => "Y",
                                                    "DELAY" => "N",
                                                    "ALLOW_MULTI_SELECT" => "Y",
                                                    "COMPONENT_TEMPLATE" => "bottom"
                                                ),
                                                false
                                            );?>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <?$APPLICATION->IncludeComponent(
                                                "bitrix:menu", 
                                                "bottom", 
                                                array(
                                                    "ROOT_MENU_TYPE" => "bottom3",
                                                    "MENU_CACHE_TYPE" => "A",
                                                    "MENU_CACHE_TIME" => "3600000",
                                                    "MENU_CACHE_USE_GROUPS" => "N",
                                                    "MENU_CACHE_GET_VARS" => array(
                                                    ),
                                                    "MAX_LEVEL" => "1",
                                                    "CHILD_MENU_TYPE" => "",
                                                    "USE_EXT" => "Y",
                                                    "DELAY" => "N",
                                                    "ALLOW_MULTI_SELECT" => "Y",
                                                    "COMPONENT_TEMPLATE" => "bottom"
                                                ),
                                                false
                                            );?>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3 col-sm-3">
                                    <div class="info">
                                        <div class="phone">
                                            <i class="fa fa-phone"></i>
                                            <div class="info_ext">
                                                <?$APPLICATION->IncludeFile(SITE_DIR."include/site-phone.php", array(), array(
                                                        "MODE" => "html",
                                                        "NAME" => "Phone",
                                                    )
                                                );?>
                                                <div class="popup_form_ext" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]["aspro_stroy_form"]["aspro_stroy_callback"][0]?>" data-name="callback">
                                                        <span><?=GetMessage("S_CALLBACK")?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="compass">

                                            <?$APPLICATION->IncludeFile(SITE_DIR."include/site-address.php", array(), array(
                                                    "MODE" => "html",
                                                    "NAME" => "�����",
                                                )
                                            );?>
                                        </div>
                                        <div class="email">
                                            <i class="fa fa-envelope"></i>
                                            <?$APPLICATION->IncludeFile(SITE_DIR."include/site-email.php", array(), array(
                                                    "MODE" => "html",
                                                    "NAME" => "E-mail",
                                                )
                                            );?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 hidden-md hidden-lg">
                            <div class="copy last">
                                <?$APPLICATION->IncludeFile(SITE_DIR."include/copy.php", Array(), Array(
                                        "MODE" => "php",
                                        "NAME" => "Copyright",
                                    )
                                );?>

                            </div>
                            <div id="bx-composite-banner"></div>
                        </div>
						<div class="col-sm-3" style="width:100%;">
                            <div class="copy last">
                              Все права защищены и охраняются в соответствии с главой 69 ГК РФ. Копирование любой информации и материалов без согласования с владельцами сайта запрещено.
Вся информация (включая цены, время проведения акций, технические характеристики изделий и прочее), представленная на данном сайте, носит исключительно информационный характер и 
ни при каких условиях не является публичной офертой, определяемой положениями статьи 437 (2) Гражданского кодекса Российской Федерации.
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <div class="bx_areas">
            <?$APPLICATION->IncludeFile(SITE_DIR."include/invis-counter.php", Array(), Array(
                    "MODE" => "text",
                    "NAME" => "Counters place for Yandex.Metrika, Google.Analytics",
                )
            );?>
        </div>
        <?/*$APPLICATION->IncludeFile(SITE_DIR."include/autoform.php", Array(), Array(
                "MODE" => "php",
                "NAME" => "AutoForm",
            )
        );*/?>
        <?CStroy::SetMeta();?>
                    <script>
                        (function(w,d,u){
                            var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
                            var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                        })(window,document,'https://crm.strlog.ru/upload/crm/site_button/loader_6_qyoaeq.js');
                    </script>
                    <script src="//cdn.callibri.ru/callibri.js" type="text/javascript" charset="utf-8"></script>
    </body>
</html>