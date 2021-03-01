<?php

use \Bitrix\Main\Application as App;
use \Bitrix\Highloadblock as HL;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

$module_id = 'strlog.userupdate';

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
Loc::loadMessages(__FILE__);

\Bitrix\Main\Loader::includeModule($module_id);

$request = App::getInstance()->getContext()->getRequest();

$aTabs = array(
	array(
		'DIV' => 'edit1',
		'TAB' => 'Вкладка 1',
		'OPTIONS' => array(
			array('field_text', 'Текстовое поле', '', array('textarea', 10, 50)),
			array('field_line', 'Еще одно поле', '', array('text', 10)),
			array('field_list', 'Что-то еще', '', array('multiselectbox', array('var1'=>'var1', 'var2'=>'var2', 'var3'=>'var3', 'var4'=>'var4'))),
		),
	),
	array(
		'DIV' => 'edit2',
		'TAB' => 'Вкладка 2',
		'TITLE' => 'Тайтл',
	),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
?>

<?php $tabControl->Begin();?>

<form method="post" action="<?=$APPLICATION->GetCurPage();?>?lang=ru&mid=strlog_userupdate" name="strlog_userupdate_settings">
	<?php 
	foreach($aTabs as $aTab){
		if($aTab['OPTIONS']){
			$tabControl->BeginNextTab();
			__AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
		}
	}
	$tabControl->BeginNextTab();
	$tabControl->Buttons();
	?>
	<input type="submit" name="Update" value="Обновить" />
	<input type="reset" name="reset" value="Сбросить" />
</form>
<?php $tabControl->End();?>






















