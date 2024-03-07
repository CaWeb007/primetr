<?php

$MESS["BENDERSAY_INCLUDECOMPONETS_TAB_SETTINGS"] = "Основные настройки";
$MESS["BENDERSAY_INCLUDECOMPONETS_USE_PARSER"] = 'Включить обработку вызовов компонентов глобально<sup><span class="required">1</span></sup>';
$MESS["BENDERSAY_INCLUDECOMPONETS_INFO"] = 'Включает обработку всех страниц целиком, за исключением <b>/bitrix/*</b>, <b>/local/*</b> на поиск вызовов компонентов <b>$APPLICATION->IncludeComponent</b><br>'
	. 'Лучше отключить галку и делать обработку вызовов только там, где это нужно. Например, в <b>result_modifier.php</b> компонента <b>news.detail</b>.<br>'
	. 'Пример регулярки для поиска подключения компонентов: "/<\?(php)?[\s+?\n?\s+]*(\$APPLICATION->IncludeComponent\(.*)\?>/Us"';
