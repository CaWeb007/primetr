<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Подключение стилей и скриптов
$this->addExternalCss($this->GetFolder() . '/style.css');
$this->addExternalJs($this->GetFolder() . '/script.js');
?>

<div id="fortune-wheel-component">
    <!-- Кнопка для открытия модального окна -->
    <button class="open-modal-button" onclick="openModal()">Открыть Колесо Фортуны</button>

    <!-- Модальное окно -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <div class="wheel-container">
                <div class="wheel" id="wheel">
                    <?php foreach ($arResult['PRIZES'] as $index => $prize): ?>
                        <div class="wheel-slice" style="transform: rotate(<?= $index * (360 / count($arResult['PRIZES'])) ?>deg);">
                            <span class="prize-name"><?= $prize['name'] ?></span>
                        </div>
                    <?php endforeach; ?>
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
    // Передаем данные из PHP в JavaScript
    const prizes = <?= json_encode($arResult['PRIZES']) ?>;
</script>