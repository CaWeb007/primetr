<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
$countPrizes = count($arResult['PRIZES']);
?>

<div id="fortune-wheel-component">

    <button class="open-modal-button" onclick="openModal()">Открыть Колесо Фортуны</button>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
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
                <button class="spin-button" onclick="spinWheel()">Крутить</button>
            </div>
            <div class="phone-input">
                <input type="text" id="phone" placeholder="Введите ваш телефон" />
                <button onclick="submitResult()">Отправить</button>
            </div>
        </div>
    </div>
</div>


<script>
    const prizes = <?= json_encode($arResult['PRIZES']) ?>;
</script>