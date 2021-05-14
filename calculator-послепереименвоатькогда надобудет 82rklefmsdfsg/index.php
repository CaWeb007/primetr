<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
$APPLICATION->SetPageProperty("description", "Калькулятор ворот");
$APPLICATION->SetPageProperty("keywords", "Калькулятор ворот");
$APPLICATION->SetPageProperty("title", "Калькулятор ворот");?><?$APPLICATION->SetTitle("Калькулятор ворот");?>
    <style>
        #gates-wrapper{
            width: 100%;
            min-width: 400px;
            height: 700px;
            position: relative;
			z-index: 1;
        }
        #section-gates-wrapper,
        #sliding-gates-wrapper,
        #sliding-sandwich-gates-wrapper,
        #swining-gates-wrapper,
        #swining-sandwich-gates-wrapper{
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            opacity: 0;
            z-index: 9;
        }
        #section-gates-wrapper{
            opacity: 1;
            z-index: 10;
        }
        .calculator-span-notice{
            display: block;
            width: 100%;
            height: auto;
            margin: 10px auto 0;
            padding: 0;
            text-align: center;
        }
        .calculator-span-price-star{
            font-size: 12px;
            font-weight: 400;
            color: #d54638;
            top: -10px;
        }
        @media screen and (min-width: 0px) and (max-width: 480px){
            #gates-wrapper{
                height: 1300px;
            }
            .calculator{
                display: block;
            }
            .calculator-section{
                width: 100%;
            }
            .button-wrapper{
                flex-wrap: wrap;
            }
            .button-total-price{
                width: 100%;
                height: auto;
                margin: 0 auto 15px;
                padding: 0;
                text-align: center;
            }
            .button-order-request-wrapper{
                width: 100%;
                height: auto;
                margin: 0 auto;
                padding: 0;
                text-align: center;
            }
            .button-order-request{
                text-align: center;
            }
        }
        @media screen and (min-width: 481px) and (max-width: 800px){
            #gates-wrapper{
                height: 1200px;
            }
            .calculator{
                display: block;
            }
            .calculator-section{
                width: 100%;
            }
        }
    </style>
    <script>
        $(document).ready(function(){
            $('.show-section-gates').click(function(){
                $('.show-section-gates-point').fadeIn(200);
                $('.show-sliding-gates-point').fadeOut(200);
                $('.show-swining-gates-point').fadeOut(200);
                $('.show-sliding-sandwich-point').fadeOut(200);
                $('.show-swining-sandwich-point').fadeOut(200);
                $('#section-gates-wrapper').css('opacity', '1').css('z-index', '1');
                $('#sliding-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
            });
            $('.show-sliding-gates').click(function(){
                $('.show-sliding-gates-point').fadeIn(200);
                $('.show-section-gates-point').fadeOut(200);
                $('.show-swining-gates-point').fadeOut(200);
                $('.show-sliding-sandwich-point').fadeOut(200);
                $('.show-swining-sandwich-point').fadeOut(200);
                $('#section-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-gates-wrapper').css('opacity', '1').css('z-index', '1');
                $('#swining-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
            });
            $('.show-swining-gates').click(function(){
                $('.show-swining-gates-point').fadeIn(200);
                $('.show-sliding-gates-point').fadeOut(200);
                $('.show-section-gates-point').fadeOut(200);
                $('.show-sliding-sandwich-point').fadeOut(200);
                $('.show-swining-sandwich-point').fadeOut(200);
                $('.show-swining-proflist-point').fadeIn(200);
                $('#section-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-gates-wrapper').css('opacity', '1').css('z-index', '1');
                $('#sliding-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
            });
            $('.show-sliding-sandwich-gates').click(function(){
                $('.show-sliding-sandwich-point').fadeIn(200);
                $('.show-swining-gates-point').fadeOut(200);
                $('.show-sliding-gates-point').fadeIn(200);
                $('.show-section-gates-point').fadeOut(200);
                $('.show-swining-sandwich-point').fadeOut(200);
                $('#section-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-sandwich-gates-wrapper').css('opacity', '1').css('z-index', '1');
                $('#swining-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
            });
            $('.show-sliding-pfoflist-gates').click(function(){
                $('.show-sliding-sandwich-point').fadeOut(200);
                $('.show-sliding-proflist-point').fadeIn(200);
                $('.show-swining-gates-point').fadeOut(200);
                $('.show-sliding-gates-point').fadeIn(200);
                $('.show-section-gates-point').fadeOut(200);
                $('#section-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-gates-wrapper').css('opacity', '1').css('z-index', '1');
                $('#swining-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
            });
            $('.show-swining-sandwich-gates').click(function(){
                $('.show-sliding-sandwich-point').fadeOut(200);
                $('.show-sliding-proflist-point').fadeOut(200);
                $('.show-swining-gates-point').fadeOut(200);
                $('.show-swining-sandwich-point').fadeIn(200);
                $('.show-swining-proflist-point').fadeOut(200);
                $('.show-swining-gates-point').fadeIn(200);
                $('.show-section-gates-point').fadeOut(200);
                $('#section-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-sandwich-gates-wrapper').css('opacity', '1').css('z-index', '1');
            });
            $('.show-swining-proflist-gates').click(function(){
                $('.show-sliding-sandwich-point').fadeOut(200);
                $('.show-sliding-proflist-point').fadeIn(200);
                $('.show-swining-gates-point').fadeOut(200);
                $('.show-swining-sandwich-point').fadeOut(200);
                $('.show-swining-proflist-point').fadeIn(200);
                $('.show-swining-gates-point').fadeIn(200);
                $('.show-section-gates-point').fadeOut(200);
                $('#section-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#sliding-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-gates-wrapper').css('opacity', '1').css('z-index', '1');
                $('#sliding-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
                $('#swining-sandwich-gates-wrapper').css('opacity', '0').css('z-index', '0');
            });
        });
    </script>

    <div id="gates-wrapper">
        <div id="section-gates-wrapper">
            <?$APPLICATION->IncludeComponent(
                "calculator:section_gates",
                ".default",
                array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    "GOAL_SECTION_CONTROLLER_MAX" => "60",
                    "GOAL_SECTION_POROG_WIDTH" => "6000",
                    "GOAL_SECTION_POROG_HEIGHT" => "3000",
                    "GOAL_SECTION_QUADR_1" => "7",
                    "GOAL_SECTION_QUADR_1_LIST" => "Комплект привода SECTIONAL-800PRO, S=11 м. кв, Н=2800мм, в составе привода Sectional-800PRO со встро",
                    "GOAL_SECTION_QUADR_2" => "9",
                    "GOAL_SECTION_QUADR_2_LIST" => "Комплект привода SECTIONAL-800PRO, S=11 м. кв, Н=2800мм, в составе привода Sectional-800PRO со встро",
                    "GOAL_SECTION_QUADR_3" => "12",
                    "GOAL_SECTION_QUADR_3_LIST" => "Привод SECTIONAL-1200 со встроенным приемником, радиокнопкой",
                    "GOAL_SECTION_QUADR_4" => "18",
                    "GOAL_SECTION_QUADR_4_LIST" => "Комплект привода Shaft-30 IP65KIT, в составе привода SHAFT-30 IP65, крепежного кронштейна, 8 метров ",
                    "GOAL_SECTION_START_WIDTH" => "2000",
                    "GOAL_SECTION_START_HEIGHT" => "2000",
                    "GOAL_SECTION_CONTROLLER_PRICE" => "800",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "N",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "GOAL_BASE" => "7000"
                ),
                false
            );?>
        </div>

        <div id="sliding-gates-wrapper">
            <?$APPLICATION->IncludeComponent(
                "calculator:sliding_gates",
                ".default",
                array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    "GOAL_SECTION_CONTROLLER_MAX" => "12",
                    "GOAL_SECTION_POROG_WIDTH" => "4000",
                    "GOAL_SECTION_POROG_HEIGHT" => "3000",
                    "GOAL_SECTION_QUADR_1" => "10",
                    "GOAL_SECTION_QUADR_1_LIST" => "Комплект привода SECTIONAL-800PRO, S=11 м. кв, Н=2800мм, в составе привода Sectional-800PRO со встро",
                    "GOAL_SECTION_QUADR_2" => "18",
                    "GOAL_SECTION_QUADR_2_LIST" => "Комплект привода Shaft-50PROKIT S=25кв.м. (DOORHAN)",
                    "GOAL_SECTION_QUADR_3" => "24",
                    "GOAL_SECTION_QUADR_3_LIST" => "Комплект привода SE14.21 380В S=50 кв. м IP65 (GFA)",
                    "GOAL_SECTION_QUADR_4" => "100",
                    "GOAL_SECTION_QUADR_4_LIST" => "Привод SECTIONAL-750 со встроенным приемником, радиокнопкой",
                    "GOAL_SECTION_START_WIDTH" => "3500",
                    "GOAL_SECTION_START_HEIGHT" => "2100",
                    "GOAL_SECTION_CONTROLLER_PRICE" => "5000",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "N",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "GOAL_BASE" => "7000",
                    "GOAL_SLIDING_START_HEIGHT" => "2000",
                    "GOAL_SLIDING_POROG_WIDTH" => "1000",
                    "GOAL_SLIDING_POROG_HEIGHT" => "1000",
                    "GOAL_SLIDING_CONTROLLER_MAX" => "60",
                    "GOAL_SLIDING_CONTROLLER_PRICE" => "1000",
                    "GOAL_SLIDING_BASE" => "17000",
                    "GOAL_SLIDING_START_WIDTH" => "3500",
                    "GOAL_SLIDING_GEAR" => "Привод SLIDING-1300 в масл. ванне для ворот весом до 1300 кг, ширина проема до 6м (DOORHAN)"
                ),
                false
            );?>
        </div>

        <div id="sliding-sandwich-gates-wrapper">
            <?$APPLICATION->IncludeComponent(
                "calculator:sliding_sandwich_gates",
                ".default",
                array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    "GOAL_SLIDINGSAND_START_WIDTH" => "3500",
                    "GOAL_SLIDINGSAND_START_HEIGHT" => "2100",
                    "GOAL_SLIDINGSAND_POROG_WIDTH" => "0",
                    "GOAL_SLIDINGSAND_POROG_HEIGHT" => "0",
                    "GOAL_SLIDINGSAND_CONTROLLER_MAX" => "60",
                    "GOAL_SLIDINGSAND_CONTROLLER_PRICE" => "800",
                    "GOAL_SLIDINGSAND_GEAR" => "Привод SLIDING-1300 в масл. ванне для ворот весом до 1300 кг, ширина проема до 6м (DOORHAN)",
                    "GOAL_SLIDINGSAND_BASE" => "17000",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_ADDITIONAL" => ""
                ),
                false
            );?>
        </div>

        <div id="swining-gates-wrapper">
            <?$APPLICATION->IncludeComponent(
                "calculator:swining_gates",
                ".default",
                array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "GOAL_SWINING_START_WIDTH" => "3500",
                    "GOAL_SWINING_START_HEIGHT" => "2000",
                    "GOAL_SWINING_POROG_WIDTH" => "3600",
                    "GOAL_SWINING_POROG_HEIGHT" => "1100",
                    "GOAL_SWINING_CONTROLLER_MAX" => "60",
                    "GOAL_SWINING_CONTROLLER_PRICE" => "1000",
                    "GOAL_SWINING_GEAR" => "Комплект привода ARM-320KIT ширина ворот до 4м (DOORHAN)",
                    "GOAL_SWINING_BASE" => "10000",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO"
                ),
                false
            );?>
        </div>

        <div id="swining-sandwich-gates-wrapper">
            <?$APPLICATION->IncludeComponent(
                "calculator:swining_sandwich_gates",
                ".default",
                array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    "GOAL_SLIDINGSAND_START_WIDTH" => "3500",
                    "GOAL_SLIDINGSAND_START_HEIGHT" => "2100",
                    "GOAL_SLIDINGSAND_POROG_WIDTH" => "0",
                    "GOAL_SLIDINGSAND_POROG_HEIGHT" => "0",
                    "GOAL_SLIDINGSAND_CONTROLLER_MAX" => "60",
                    "GOAL_SLIDINGSAND_CONTROLLER_PRICE" => "800",
                    "GOAL_SLIDINGSAND_GEAR" => "Привод SLIDING-1300 в масл. ванне для ворот весом до 1300 кг, ширина проема до 6м (DOORHAN)",
                    "GOAL_SLIDINGSAND_BASE" => "17000",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "GOAL_SWININGSAND_START_WIDTH" => "3500",
                    "GOAL_SWININGSAND_START_HEIGHT" => "1500",
                    "GOAL_SWININGSAND_POROG_WIDTH" => "0",
                    "GOAL_SWININGSAND_POROG_HEIGHT" => "0",
                    "GOAL_SWININGSAND_CONTROLLER_MAX" => "60",
                    "GOAL_SWININGSAND_CONTROLLER_PRICE" => "800",
                    "GOAL_SWININGSAND_GEAR" => "Привод SLIDING-1300PRO в маслянной ванне для ворот весом до 1300 кг, шириной до 6м (DOORHAN)",
                    "GOAL_SWININGSAND_BASE" => "10000"
                ),
                false
            );?>
        </div>

    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>