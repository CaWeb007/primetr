<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
$this->addExternalCss($this->GetFolder().'/font/stylesheet.css');
/** @var array $arResult
 * [PRIZES=>array,
 * WHEEL_CONFIG=>[ROTATE_ANGLE_SLICE, CLIP_PATH],
 * WIN_INFO=>[STATUS, PRIZE, ANGLE],
 * JS_OPTION=>[STATUS, PRIZE, RESULT_IBLOCK_ID, ROTATE, TIMER]]
 */
?>

<div class="fortune-wheel" id="fortune_wheel_component">
    <div data-b24-crm-button-block-button="" class="b24-widget-button-inner-container fortune-wheel-open-button">
        <div data-b24-crm-button-block-border="" class="b24-widget-button-inner-mask" style="background: #D64438;"></div>
        <div class="b24-widget-button-block">
            <div data-b24-crm-button-pulse="" class="b24-widget-button-pulse b24-widget-button-pulse-animate" style="border-color: #D64438;"></div>
            <div data-b24-crm-button-block-inner="" class="b24-widget-button-inner-block" style="background: #D64438;">
                <div class="b24-widget-button-icon-container">
                    <div data-b24-crm-button-icon="openline" class="b24-widget-button-inner-item b24-widget-button-icon-animation" style="">
                        <img src="<?=$this->GetFolder()?>/images/icon_button.svg" alt="" width="28" height="29">
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="fortune-wheel-modal ">
        <div class="fortune-wheel-modal-container">
            <button class="fortune-wheel-modal-container-close-button"></button>
            <div class="fortune-wheel-modal-container-content">
                <div class="fw-form">
                    <?if ($arResult['WIN_INFO']['STATUS'] === 'START'):?>
                        <div class="fw-form-container fw-form-container-start">
                            <div class="fw-form-container-title"><?=Loc::getMessage('FORTUNE_WHEEL_TITLE')?></div>
                            <div class="fw-form-container-text"><span><?=Loc::getMessage('FORTUNE_WHEEL_TEXT')?></span></div>
                            <div class="fw-form-container-input-container">
                                <div class="fw-form-container-input-container-label">
                                    <span><?=Loc::getMessage('FORTUNE_WHEEL_LABEL')?></span>
                                </div>
                                <div class="fw-form-container-input-container-input">
                                    <input type="text">
                                    <div class="fw-form-container-input-container-success-icon"></div>
                                    <div class="fw-form-container-input-container-error-icon"></div>
                                    <div class="fw-form-container-input-container-error"><?=Loc::getMessage('FORTUNE_WHEEL_ERROR')?></div>
                                </div>
                            </div>
                            <div class="fw-form-container-button-container">
                                <button disabled><?=Loc::getMessage('FORTUNE_WHEEL_BUTTON')?></button>
                            </div>
                        </div>
                    <?endif?>
                    <div class="fw-form-container fw-form-container-end"<?=($arResult['WIN_INFO']['STATUS'] === 'END') ? ' style="display:flex"' : ''?>>
                        <div class="fw-form-container-title"><?=Loc::getMessage('FORTUNE_WHEEL_END_TITLE')?></div>
                        <div class="fw-form-container-prize"><?=($arResult['WIN_INFO']['STATUS'] === 'END') ? $arResult['WIN_INFO']['PRIZE'] : ''?></div>
                        <div class="fw-form-container-text"><?=Loc::getMessage('FORTUNE_WHEEL_END_TEXT')?></div>
                        <div class="fw-form-container-button-container">
                            <button><?=Loc::getMessage('FORTUNE_WHEEL_END_BUTTON')?></button>
                        </div>
                    </div>
                </div>

                <div class="fw-wheel-container">
                    <div class="fw-wheel-container-viewport">

                        <div class="fw-wheel-border"></div>
                        <div class="fw-wheel-triangle"></div>

                        <?foreach ($arResult['PRIZES'] as $index => $item):?>
                            <?
                            $angle = ($index + 1) * $arResult['WHEEL_CONFIG']['ROTATE_ANGLE_SLICE'] - $arResult['WHEEL_CONFIG']['ROTATE_ANGLE_SLICE'] /2;
                            if ($angle === 90) continue;
                            ?>
                            <div class="fw-wheel-mini-triangle" style="transform: rotate(<?=$angle?>deg);"></div>
                        <?endforeach?>

                        <div class="fw-wheel" style="
                                transition-duration: <?=$arResult['WHEEL_CONFIG']['TIMER']?>ms;
                                <?=($arResult['WIN_INFO']['STATUS'] === 'END') ? 'transform: rotate('.$arResult['WIN_INFO']['ANGLE'].'deg)' : ''?>
                                ">
                            <?foreach ($arResult['PRIZES'] as $index => $item):?>
                                <?
                                $angle = $index * $arResult['WHEEL_CONFIG']['ROTATE_ANGLE_SLICE'];
                                ?>
                                <div class="fw-wheel-slice" style="transform: rotate(<?=$angle;?>deg);  clip-path: polygon(<?=$arResult['WHEEL_CONFIG']['CLIP_PATH']?>)">
                                    <div class="fw-wheel-slice-prize-name">
                                        <?=$item['NAME']?>
                                    </div>
                                </div>
                            <?endforeach?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    fortuneWheel(<?=$arResult['JS_OPTION']?>)
</script>