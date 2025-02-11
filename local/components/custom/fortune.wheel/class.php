<?

class FortuneWheelComponent extends CBitrixComponent implements \Bitrix\Main\Engine\Contract\Controllerable
{
    // Вебхук для отправки лидов
    private $webhookUrl = 'https://crm.strlog.ru/rest/52/ewopeeg6jjzaudzo/';

    // Символьный код инфоблока
    private $iblockCode = 'prizes';

    public function executeComponent()
    {

        // Получаем призы из инфоблока
        $this->arResult['PRIZES'] = $this->getPrizesFromInfoblock();

        // Подключение шаблона
        $this->includeComponentTemplate();
    }

    // Метод для создания инфоблока
    /*
    \CBitrixComponent::includeComponentClass('custom:order.generate.pdf');
    FortuneWheelComponent::createPrizesInfoblock();
    */
    public static function createPrizesInfoblock()
    {
        // Проверяем, существует ли инфоблок
        $iblock = CIBlock::GetList([], ['CODE' => 'prizes'])->Fetch();
        if ($iblock) {
            return; // Инфоблок уже существует
        }

        // Создаем инфоблок
        $iblockFields = [
            'ACTIVE' => 'Y',
            'NAME' => 'Призы для Колеса Фортуны',
            'CODE' => 'prizes',
            'IBLOCK_TYPE_ID' => 'aspro_stroy_content', // Тип инфоблока
            'SITE_ID' => ['s1'], // Привязка к сайту
            'GROUP_ID' => ['2' => 'R'], // Права доступа
        ];
        $o = new \CIBlock();
        $iblockId = $o->Add($iblockFields);
        if (!$iblockId) {
            throw new Exception('Ошибка при создании инфоблока: ' . $o->LAST_ERROR);
        }

        // Создаем свойство "Вероятность"
        $propertyFields = [
            'NAME' => 'Вероятность',
            'CODE' => 'PROBABILITY',
            'IBLOCK_ID' => $iblockId,
            'PROPERTY_TYPE' => 'N', // Числовой тип
            'IS_REQUIRED' => 'Y', // Обязательное поле
        ];

        $property = new CIBlockProperty;
        $propertyId = $property->Add($propertyFields);
        if (!$propertyId) {
            throw new Exception('Ошибка при создании свойства: ' . $property->LAST_ERROR);
        }
    }

    // Метод для получения призов из инфоблока
    private function getPrizesFromInfoblock()
    {
        $prizes = [];

        // Получаем ID инфоблока по символьному коду
        $iblock = CIBlock::GetList([], ['CODE' => $this->iblockCode])->Fetch();
        if (!$iblock) {
            return $prizes;
        }

        // Параметры для выборки элементов
        $filter = ['IBLOCK_ID' => $iblock['ID'], 'ACTIVE' => 'Y'];
        $select = ['ID', 'NAME', 'PROPERTY_PROBABILITY'];

        // Получаем элементы инфоблока
        $dbItems = CIBlockElement::GetList([], $filter, false, false, $select);
        while ($item = $dbItems->Fetch()) {
            $prizes[] = [
                'name' => $item['NAME'],
                'probability' => (int)$item['PROPERTY_PROBABILITY_VALUE'],
            ];
        }

        return $prizes;
    }

    public function configureActions()
    {
        return [
            'saveResult' => [
                'prefilters' => [],
            ],
        ];
    }

    public function saveResultAction($phone, $prize)
    {
        // Валидация телефона
        if (empty($phone) || !preg_match('/^\+?\d{10,15}$/', $phone)) {
            return ['success' => false, 'message' => 'Некорректный номер телефона'];
        }

        // Отправка лида в Битрикс24
        $result = $this->sendLeadToBitrix24($phone, $prize);

        if ($result) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Ошибка при отправке лида в Битрикс24'];
        }
    }

    private function sendLeadToBitrix24($phone, $prize)
    {
        // Данные для создания лида
        $managerId = 4221;
        $leadData = [
            'TITLE' => 'Заявка с формы на сайте "Периметр": Колесо фортуны',
            'NAME' => 'Клиент: '.$phone,
            'PHONE' => [['VALUE' => $phone, 'VALUE_TYPE' => 'WORK']],
            'COMMENTS' => "Выигранный приз: $prize",
            "ASSIGNED_BY_ID" => $managerId,
            "SOURCE_ID" => 'UC_8C944E',
            "STATUS_ID" => "NEW",
            "OPENED" => "Y"
        ];

        // Отправка запроса через вебхук
        $queryUrl = $this->webhookUrl . 'crm.lead.add.json';
        $queryData = http_build_query(['fields' => $leadData]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $queryUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $queryData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $responseData = json_decode($response, true);

        // Проверка успешности запроса
        return isset($responseData['result']);
    }
}