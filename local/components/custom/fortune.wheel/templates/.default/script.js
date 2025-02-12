let currentPrize = null;

// Функции для управления модальным окном
function openModal() {
    document.getElementById('modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

// Логика вращения колеса
function spinWheel() {
    const wheel = document.getElementById('wheel');
    const randomDegree = Math.floor(Math.random() * 360) + 1440; // Минимум 4 полных оборота
    wheel.style.transform = `rotate(${randomDegree}deg)`;

    // Задержка для определения выигрыша
    setTimeout(() => {
        currentPrize = getRandomPrize(prizes);
        setStatus();
        alert(`Вы выиграли: ${currentPrize.name}`);

    }, 5000); // 5 секунд — время анимации
}

// Функция для выбора приза с учетом вероятностей
function getRandomPrize(prizes) {
    const totalProbability = prizes.reduce((sum, prize) => sum + prize.probability, 0);
    const randomValue = Math.random() * totalProbability;

    let cumulativeProbability = 0;
    for (const prize of prizes) {
        cumulativeProbability += prize.probability;
        if (randomValue <= cumulativeProbability) {
            return prize;
        }
    }
}

// Отправка данных на сервер
function submitResult() {
    const phone = document.getElementById('phone').value;
    if (!phone) {
        alert("Пожалуйста, введите ваш телефон.");
        return;
    }

    if (!currentPrize) {
        alert("Сначала крутите колесо!");
        return;
    }

    // Отправляем данные на сервер
    BX.ajax.runComponentAction('custom:fortune.wheel', 'saveResult', {
        mode: 'class',
        data: {
            phone: phone,
            prize: currentPrize.name
        }
    }).then(response => {
        if (response.data.success) {
            alert("Данные успешно сохранены!");
        } else {
            alert("Ошибка при сохранении данных.");
        }
    });
}
function setStatus(){
    BX.ajax.runComponentAction('custom:fortune.wheel', 'saveStatus', {
        mode: 'class',
        data: {
            prize: currentPrize.name
        }
    }).then(response => {
        if (response.data.success) {
            console.log("Данные успешно сохранены!");
        } else {
            console.log("Ошибка при сохранении данных.");
        }
    });
}

// Закрытие модального окна при клике вне его
window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target === modal) {
        closeModal();
    }
};