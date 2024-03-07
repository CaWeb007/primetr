<?php
namespace Bendersay\Includecomponets;

/**
 * Description of includecomponent
 *
 * @author ASayants
 */
class Includecomponent {

	/**
	 * ���������� ���������� ��� �������������� �������� ���������
	 */
	public function IncludeHTMLEditorComponents() {
        if((int)$_REQUEST['IBLOCK_ID'] !== 10) return;
		// �������� ������ �����������
		\Bitrix\Main\Loader::includeModule('fileman');
		$arr_components = \CHTMLEditor::GetComponents([]);
		$js_ob = \CUtil::PhpToJSObject($arr_components);

		\CComponentParamsManager::Init(); // �������������� oBXComponentParamsManager
		?>
		<script>
			BX.ready(function () {
				BX.addCustomEvent('OnEditorInitedBefore', function (toolbar) {

					this.config.components = <?= $js_ob ?>;	// ������ �����������	
					this.showComponents = true;	// ����� �����������
					this.allowPhp = true;	// ��������� PHP ��� � ���������

				});
			});
		</script>
		<?
	}

}
