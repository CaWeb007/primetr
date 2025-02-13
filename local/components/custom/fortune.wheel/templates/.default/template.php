<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult[PRIZES=>array, COOKIE_PRIZE=>array] */
$countPrizes = count($arResult['PRIZES']);
$currentPrize = $arResult['COOKIE_PRIZE']['PRIZE'];
$status = $arResult['COOKIE_PRIZE']['STATUS'];
?>

<div id="fortune-wheel-component">

    <button class="open-modal-button">Открыть Колесо Фортуны</button>

    <div id="modal" class="fortune-wheel-modal">
        <div class="modal-content">
            <span class="close-button"><i class="fa fa-times" aria-hidden="true"></i></span>
            <?if(empty($currentPrize)):?>
                <div class="wheel-container">
                    <div class="wheel" id="wheel">
                        <?foreach ($arResult['PRIZES'] as $index => $prize): ?>
                            <div
                                class="wheel-slice"
                                style="transform:
                                    rotate(<?=$index * (360 / $countPrizes)?>deg)
                                    skew(<?=90 - (360 / $countPrizes)?>deg);"
                            >
                                <span
                                    class="prize-name"
                                    style="transform:
                                        skew(<?=(360 / $countPrizes - 90)?>deg)
                                        rotate(<?=(360 / $countPrizes / 2 )?>deg);"
                                >
                                    <?=$prize['name'] ?>
                                </span>
                            </div>
                        <?endforeach;?>
                    </div>
                    <button class="spin-button" >Крутить</button>
                </div>
            <?endif?>
            <div class="currentPrizeContainer"<?if($currentPrize) echo ' style="display: block"'?>>
                <span class="text">Поздравляем вы выиграли:</span>
                <span class="currentPrize"><?=$currentPrize?></span>
            </div>
            <?if($status !== 'END'):?>
                <div class="phone-input-container"<?if($status === 'PRIZE') echo ' style="display: block"'?>>
                    <input type="text" id="phone" placeholder="Введите ваш телефон" />
                    <button class="submit">Отправить</button>
                </div>
            <?endif?>
            <div class="thanks-text"<?if($status === 'END') echo ' style="display: block"'?>>
                Спасибо за участие, мы вам перезвоним
            </div>
        </div>
    </div>
</div>


<script>
    fortuneWheel(<?= json_encode($arResult) ?>)
</script>