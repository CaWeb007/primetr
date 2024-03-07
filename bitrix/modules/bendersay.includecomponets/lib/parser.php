<?php

namespace Bendersay\Includecomponets;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;

/**
 * Парсит станицу и исполняет вызов компонентов
 *
 * @author ASayants
 */
class Parser {

	/**
	 * Подключает компоненты на странице
	 * @param type $buffer
	 * @return string
	 */
	public function ComponentInclude(&$buffer) {
		
		if(self::CheckPath()) {
			$new_string = preg_replace_callback(
				'/<\?(php)?[\s+?\n?\s+]*(\$APPLICATION->IncludeComponent\(.*)\?>/Us', function ($matches) {

					global $APPLICATION;

					ob_start();
					eval($matches[2]);
					return ob_get_clean();
				}, $buffer
			);
			$buffer = $new_string;
		}
	}
	
	/**
	 * Проверяет текущий путь
	 * @return boolean
	 */
	public static function CheckPath() {
		if (Option::get("bendersay.includecomponets", "use_parser") != 'Y') {
			return false;
		}
		
		$request = Application::getInstance()->getContext()->getRequest();
		
		if ($request->getQuery('bxsender') == 'core_window_cdialog') {
			return false;
		}
		
		$requestedDir = $request->getRequestedPageDirectory();
		$arr_path = ['/bitrix/', '/local/'];
		foreach ($arr_path as $path) {
			if($request->isAdminSection() && strripos($requestedDir, $path) !== false) {
				return false;
			}
		}
		
		return true;
	}

}
