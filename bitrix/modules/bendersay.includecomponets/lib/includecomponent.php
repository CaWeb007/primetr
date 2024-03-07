<?php
namespace Bendersay\Includecomponets;

/**
 * Description of includecomponent
 *
 * @author ASayants
 */
class Includecomponent {

	/**
	 * Подключает компоненты при редактировании элемента инфоблока
	 */
	public function IncludeHTMLEditorComponents() {
        if((int)$_REQUEST['IBLOCK_ID'] !== 10) return;
		// Получаем список компонентов
		\Bitrix\Main\Loader::includeModule('fileman');
		$arr_components = \CHTMLEditor::GetComponents([]);
		$js_ob = \CUtil::PhpToJSObject($arr_components);

		\CComponentParamsManager::Init(); // инициализирует oBXComponentParamsManager
		?>
		<script>
			BX.ready(function () {
				BX.addCustomEvent('OnEditorInitedBefore', function (toolbar) {

					this.config.components = <?= $js_ob ?>;	// Список компонентов	
					this.showComponents = true;	// показ компонентов
					this.allowPhp = true;	// разрешаем PHP код в редакторе

				});
			});
		</script>
		<?
	}

}
