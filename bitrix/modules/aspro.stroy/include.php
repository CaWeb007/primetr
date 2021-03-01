<?php
/**
 * CStroy module
 * @copyright 2016 Aspro
 */
 
CModule::AddAutoloadClasses(
	'aspro.stroy',
	array(
		'stroy' => 'install/index.php',
		'CCache' => 'classes/general/CCache.php',
		'CStroy' => 'classes/general/CStroy.php',
		'CStroyTools' => 'classes/general/CStroyTools.php',
	)
);

// include common aspro functions
include_once __DIR__ .'/classes/general/CCache.php';

CStroy::UpdateFrontParametrsValues();
CStroy::GenerateThemes();

// event handlers for component aspro:form.stroy
AddEventHandler('iblock', 'OnAfterIBlockPropertyUpdate', array('CStroy', 'UpdateFormEvent'));
AddEventHandler('iblock', 'OnAfterIBlockPropertyAdd', array('CStroy', 'UpdateFormEvent'));