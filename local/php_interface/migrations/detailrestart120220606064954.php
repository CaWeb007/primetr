<?php

namespace Sprint\Migration;


class detailrestart120220606064954 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.0.6";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $iblockId = $helper->Iblock()->getIblockIdIfExists('aspro_stroy_catalog', 'aspro_stroy_catalog');
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Описание (Правая сторона)',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'DESC_RIGHT_SIDE',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'F',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => '',
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Таблица размеров (TAB)',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'DESCRIPTION_SIZETABLE',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'F',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'Y',
  'XML_ID' => '',
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'Y',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Тип поверхности (TAB)',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'DESCRIPTION_SURFTYPE',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'F',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'Y',
  'XML_ID' => '',
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'Y',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Комплектация (TAB)',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'DESCRIPTION_EQUIP',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'F',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'Y',
  'XML_ID' => '',
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'Y',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Показывать блоки',
  'ACTIVE' => 'Y',
  'SORT' => '1001',
  'CODE' => 'DETAIL_BLOCKS',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'L',
  'ROW_COUNT' => '3',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'C',
  'MULTIPLE' => 'Y',
  'XML_ID' => '',
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
  'VALUES' => 
  array (
    0 => 
    array (
      'VALUE' => 'Замер',
      'DEF' => 'N',
      'SORT' => '1',
      'XML_ID' => '348c97f629a732950b882e6e1bbd623b',
    ),
    1 => 
    array (
      'VALUE' => 'Монтаж',
      'DEF' => 'N',
      'SORT' => '2',
      'XML_ID' => 'c0390e56c24408fd4754ca5ebd0a850c',
    ),
    2 => 
    array (
      'VALUE' => 'Доставка',
      'DEF' => 'N',
      'SORT' => '3',
      'XML_ID' => '32020d8059ea7cc20d54d5b5b09d83f4',
    ),
  ),
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Стоимость монтажа',
  'ACTIVE' => 'Y',
  'SORT' => '1001',
  'CODE' => 'MOUNTING_PRICE',
  'DEFAULT_VALUE' => '4000',
  'PROPERTY_TYPE' => 'N',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '5',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => '',
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->UserOptions()->saveElementForm($iblockId, array (
  'Товар|edit1' => 
  array (
    'XML_ID' => 'Внешний код',
    'ACTIVE' => 'Активность',
    'NAME' => 'Название',
    'CODE' => 'Символьный код',
    'SORT' => 'Сортировка',
    'IBLOCK_ELEMENT_PROP_VALUE' => 'Значения свойств',
    'PROPERTY_TIZERS' => 'Тизеры',
    'PROPERTY_PRICEOLD' => 'Старая цена для сайта',
    'PROPERTY_PRICE' => 'Цена для сайта',
    'PROPERTY_FILTER_PRICE' => 'Цена',
    'PROPERTY_SIZES_PRICE_AFTER' => 'Текущая цена для размера',
    'PROPERTY_SIZES_PRICE_BEFORE' => 'Старая цена для размера',
    'PROPERTY_TYPE_BUILDINGS' => 'Технологии строительства',
    'PROPERTY_FORM_QUESTION' => 'Задать вопрос',
    'PROPERTY_FORM_ORDER' => 'Заказать товар',
    'PROPERTY_SHOW_ON_INDEX_PAGE' => 'Отображать в слайдере на главной',
    'PROPERTY_PRODUCT_LOGO' => 'Лого продукта',
    'PROPERTY_ARTICLE' => 'Артикул',
    'PROPERTY_TYPE' => 'Тип',
    'PROPERTY_PLOSHAD' => 'Площадь ворот',
    'PROPERTY_SIZEV' => 'Максимальный вес полотна ворот',
    'PROPERTY_SKOROST' => 'Скорость вращения мотора',
    'PROPERTY_KRYTMOMENT' => 'Крутящий момент',
    'PROPERTY_COLOR' => 'Напряжение питания',
    'PROPERTY_ELECTRODV' => 'Электродвигатель',
    'PROPERTY_DVV' => 'Двигатель',
    'PROPERTY_SKOROST_OT' => 'Скорость открывания',
    'PROPERTY_PREDOHRAN' => 'Предохранители',
    'PROPERTY_PEREDACHA' => 'Передача движения',
    'PROPERTY_REZHIM' => 'Режим ожидания',
    'PROPERTY_VETR' => 'Ветровая нагрузка',
    'PROPERTY_USIL' => 'Усилие ручного открывания и закрывания',
    'PROPERTY_GRGOR' => 'Группа горючести по ГОСТ 30244-94',
    'PROPERTY_SIZE_ZHILAYA' => 'Максимальное усиление',
    'PROPERTY_GRVOS' => 'Группа воспламеняемости по ГОСТ 30402-96',
    'PROPERTY_VESP' => 'Вес полотна',
    'PROPERTY_SIZE_KUXNI' => 'Мощность',
    'PROPERTY_DIAP' => 'Диапазон рабочих температур',
    'PROPERTY_MOMENT' => 'Максимальный действующий момент',
    'PROPERTY_IP' => 'Класс защиты IP',
    'PROPERTY_IN' => 'Интенсивность использования',
    'PROPERTY_TEPLOP' => 'Теплопроводность',
    'PROPERTY_ZVUKOIZ' => 'Звукоизоляция',
    'PROPERTY_VODONEP' => 'Водонепроницаемость',
    'PROPERTY_PREDEL_OG' => 'Предел огнестойкости',
    'PROPERTY_SOPRTEP' => 'Сопротивление теплопередаче полотна',
    'PROPERTY_SOPRVETR' => 'Сопротивление ветровой нагрузке',
    'PROPERTY_KOLCICL' => 'Количество циклов открывания/закрывания',
    'PROPERTY_OBVOZD' => 'Объемная воздухопроницаемость',
    'PROPERTY_GARAGE' => 'Гараж',
    'PROPERTY_SLEEP_ROOM' => 'Количество спален',
    'PROPERTY_APPOINTMENT' => 'Назначение',
    'PROPERTY_SIZES' => 'Размеры',
    'PROPERTY_rating' => 'Рейтинг',
    'PROPERTY_STIKERS' => 'Стикеры',
    'PROPERTY_PLANIROVKA' => 'Схемы',
    'PROPERTY_UF_FRONT_SORT' => 'Сортировка на главной',
    'PROPERTY_UF_SHOW_FRONT' => 'Поместить на главную',
  ),
  'Табы|cedit2' => 
  array (
    'PROPERTY_DESCRIPTION_DESK' => 'Описание (TAB)',
    'PROPERTY_DESC_RIGHT_SIDE' => 'Описание (Правая сторона)',
    'PROPERTY_DESCRIPTION_EQUIP' => 'Комплектация (TAB)',
    'PROPERTY_DESCRIPTION_SIZETABLE' => 'Таблица размеров (TAB)',
    'PROPERTY_DESCRIPTION_SURFTYPE' => 'Тип поверхности (TAB)',
    'PROPERTY_DESCRIPTION_ADDEQUIP' => 'Доп комплектация (TAB)',
  ),
  'Анонс|edit5' => 
  array (
    'PREVIEW_PICTURE' => 'Картинка для анонса',
    'PREVIEW_TEXT' => 'Описание для анонса',
  ),
  'Подробно|edit6' => 
  array (
    'DETAIL_PICTURE' => 'Детальная картинка',
    'PROPERTY_PHOTOS' => 'Галерея',
    'PROPERTY_BIG_PHOTOS' => 'Галерея большая',
    'PROPERTY_DOCUMENTS' => 'Документы',
    'DETAIL_TEXT' => 'Детальное описание',
    'PROPERTY_SLIDER_VIDEO' => 'Видео для слайдера',
    'PROPERTY_DETAIL_BLOCKS' => 'Показывать блоки',
    'PROPERTY_MOUNTING_PRICE' => 'Стоимость монтажа',
  ),
  'Связи|cedit1' => 
  array (
    'PROPERTY_LINK_PROJECTS' => 'Проекты',
    'LINKED_PROP' => 'Связанные элементы',
  ),
  'SEO|edit14' => 
  array (
    'IPROPERTY_TEMPLATES_ELEMENT_META_TITLE' => 'Шаблон META TITLE',
    'IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS' => 'Шаблон META KEYWORDS',
    'IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION' => 'Шаблон META DESCRIPTION',
    'IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE' => 'Заголовок элемента',
    'IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE' => 'Настройки для картинок анонса элементов',
    'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT' => 'Шаблон ALT',
    'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE' => 'Шаблон TITLE',
    'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME' => 'Шаблон имени файла',
    'IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE' => 'Настройки для детальных картинок элементов',
    'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT' => 'Шаблон ALT',
    'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE' => 'Шаблон TITLE',
    'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME' => 'Шаблон имени файла',
    'SEO_ADDITIONAL' => 'Дополнительно',
    'TAGS' => 'Теги',
  ),
  'Разделы|edit2' => 
  array (
    'SECTIONS' => 'Разделы',
  ),
));
        $helper->UserOptions()->saveElementList($iblockId, array (
  'page_size' => '20',
  'order' => 'desc',
  'by' => 'timestamp_x',
  'columns' => 
  array (
    0 => 'NAME',
    1 => 'PREVIEW_PICTURE',
    2 => 'PROPERTY_PRICE',
    3 => 'PROPERTY_COLOR',
    4 => 'PROPERTY_SIZEV',
    5 => 'PROPERTY_SIZE_ZHILAYA',
    6 => 'ACTIVE',
    7 => 'SORT',
    8 => 'TIMESTAMP_X',
    9 => 'ID',
  ),
));

    }

    public function down()
    {
        //your code ...
    }
}
