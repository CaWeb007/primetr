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

            <div class="flex-conteiner">

                <div class="flex">

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
                                >
                                    <?=$prize['name'] ?>
                                </span>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>

                </div>

                <div class="flex">

                    <div class="currentPrizeContainer"<?if($currentPrize) echo ' style="display: block"'?>>
                        <span class="text">Поздравляем вы выиграли:</span>
                        <span class="currentPrize"><?=$currentPrize?></span>
                    </div>

                    <?if($status !== 'END'):?>
                        <div class="phone-input-container">
                            <input type="text" id="phone" placeholder="Введите ваш телефон" />
                            <button class="submit btn-lg btn btn-default">Отправить</button>
                        </div>
                    <?endif?>

                </div>

            </div>







        </div>
    </div>
</div>


<script>
    fortuneWheel(<?= json_encode($arResult) ?>)
</script>
<?
/*
 *
                    'PHONE_MASK': '+7 (999) 999-99-99',
                    'VALIDATE_PHONE_MASK': '^[+][0-9] [(][0-9]{3}[)] [0-9]{3}[-][0-9]{2}[-][0-9]{2}$',
 * */
?>