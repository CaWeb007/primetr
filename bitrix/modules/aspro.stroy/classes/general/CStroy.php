<?
/**
 * Stroy module
 * @copyright 2016 Aspro
 */

if(!defined('STROY_MODULE_ID')){
	define('STROY_MODULE_ID', 'aspro.stroy');
}

IncludeModuleLangFile(__FILE__);
use \Bitrix\Main\Type\Collection;

// initialize module parametrs list and default values
include_once __DIR__.'/../../parametrs.php';

class CStroy{
	const MODULE_ID = STROY_MODULE_ID;
	const PARTNER_NAME = 'aspro';
	const SOLUTION_NAME	= 'stroy';
	const devMode = false; // set to false before release

	static $arParametrsList = array();
	private static $arMetaParams = array();

	public function checkModuleRight($reqRight = 'R', $bShowError = false){
		global  $APPLICATION;

		if($APPLICATION->GetGroupRight(self::MODULE_ID) < $reqRight){
			if($bShowError){
				$APPLICATION->AuthForm(GetMessage('STROY_ACCESS_DENIED'));
			}
			return false;
		}

		return true;
	}

	function GetBackParametrsValues($SITE_ID, $bStatic = true){
		if($bStatic){
			static $arValues;
		}
		if($bStatic && $arValues === NULL || !$bStatic){
			$arDefaultValues = $arValues = array();
			if(self::$arParametrsList && is_array(self::$arParametrsList)){
				foreach(self::$arParametrsList as $blockCode => $arBlock){
					if($arBlock['OPTIONS'] && is_array($arBlock['OPTIONS'])){
						foreach($arBlock['OPTIONS'] as $optionCode => $arOption){
							$arDefaultValues[$optionCode] = $arOption['DEFAULT'];
						}
					}
				}
			}
			$arValues = unserialize(COption::GetOptionString(self::MODULE_ID, 'OPTIONS', serialize(array()), $SITE_ID));
			if($arValues && is_array($arValues)){
				foreach($arValues as $optionCode => $arOption){
					if(!isset($arDefaultValues[$optionCode])){
						unset($arValues[$optionCode]);
					}
				}
			}
			if($arDefaultValues && is_array($arDefaultValues)){
				foreach($arDefaultValues as $optionCode => $arOption){
					if(!isset($arValues[$optionCode])){
						$arValues[$optionCode] = $arOption;
					}
				}
			}

			if(!defined('ADMIN_SECTION') && $arValues['USE_CAPTCHA_FORM'] === 'RECAPTCHA'){
				if(!defined('RECAPTCHA_SITE_KEY')){
					define('RECAPTCHA_SITE_KEY', $arValues['RECAPTCHA_SITE_KEY']);
				}
				if(!defined('RECAPTCHA_SECRET_KEY')){
					define('RECAPTCHA_SECRET_KEY', $arValues['RECAPTCHA_SECRET_KEY']);
				}
			}
		}
		return $arValues;
	}

	function GetFrontParametrsValues($SITE_ID){
		if(!strlen($SITE_ID)){
			$SITE_ID = SITE_ID;
		}
		$arBackParametrs = self::GetBackParametrsValues($SITE_ID);
		if($arBackParametrs['THEME_SWITCHER'] === 'Y'){
			$arValues = array_merge((array)$arBackParametrs, (array)$_SESSION['THEME'][$SITE_ID]);
		}
		else{
			$arValues = (array)$arBackParametrs;
		}
		return $arValues;
	}

	function CheckColor($strColor){
		$strColor = substr(str_replace('#', '', $strColor), 0, 6);
		$strColor = base_convert(base_convert($strColor, 16, 2), 2, 16);
		for($i = 0, $l = 6 - mb_strlen($strColor); $i < $l; ++$i)
			$strColor = '0'.$strColor;
		return $strColor;
	}

	function UpdateFrontParametrsValues(){
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		if($arBackParametrs['THEME_SWITCHER'] === 'Y'){
			if($_REQUEST){
				if($_REQUEST['THEME'] === 'default'){
					if(self::$arParametrsList && is_array(self::$arParametrsList)){
						foreach(self::$arParametrsList as $blockCode => $arBlock){
							unset($_SESSION['THEME'][SITE_ID]);
							$_SESSION['THEME'][SITE_ID] = null;
						}
					}
					COption::SetOptionString(self::MODULE_ID, "NeedGenerateCustomTheme", 'Y', '', SITE_ID);
				}
				else{
					if(self::$arParametrsList && is_array(self::$arParametrsList)){
						foreach(self::$arParametrsList as $blockCode => $arBlock){
							if($arBlock['OPTIONS'] && is_array($arBlock['OPTIONS'])){
								foreach($arBlock['OPTIONS'] as $optionCode => $arOption){
									if($arOption['THEME'] === 'Y'){
										if(isset($_REQUEST[$optionCode])){
											if($optionCode == 'BASE_COLOR_CUSTOM'){
												$_REQUEST[$optionCode] = self::CheckColor($_REQUEST[$optionCode]);
											}
											if($optionCode == 'BASE_COLOR' && $_REQUEST[$optionCode] === 'CUSTOM'){
												COption::SetOptionString(self::MODULE_ID, "NeedGenerateCustomTheme", 'Y', '', SITE_ID);
											}
											if(isset($arOption['LIST'])){
												if(isset($arOption['LIST'][$_REQUEST[$optionCode]])){
													$_SESSION['THEME'][SITE_ID][$optionCode] = $_REQUEST[$optionCode];
												}
												else{
													$_SESSION['THEME'][SITE_ID][$optionCode] = $arOption['DEFAULT'];
												}
											}
											else{
												$_SESSION['THEME'][SITE_ID][$optionCode] = $_REQUEST[$optionCode];
											}
											$bChanged = true;
										}
									}
								}
							}
						}
					}
				}
				if(isset($_REQUEST["BASE_COLOR"]) && $_REQUEST["BASE_COLOR"]){
					LocalRedirect($_SERVER["HTTP_REFERER"]);
				}
			}
		}
		else{
			unset($_SESSION['THEME'][SITE_ID]);
		}
	}

	function GenerateThemes(){
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		$arBaseColors = self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR']['LIST'];
		$isCustomTheme = $_SESSION['THEME'][SITE_ID]['BASE_COLOR'] === 'CUSTOM';

		$bNeedGenerateAllThemes = COption::GetOptionString(self::MODULE_ID, 'NeedGenerateThemes', 'N', SITE_ID) === 'Y';
		$bNeedGenerateCustomTheme = COption::GetOptionString(self::MODULE_ID, 'NeedGenerateCustomTheme', 'N', SITE_ID) === 'Y';

		$baseColorCustom = '';
		$lastGeneratedBaseColorCustom = COption::GetOptionString(self::MODULE_ID, 'LastGeneratedBaseColorCustom', '', SITE_ID);
		if(isset(self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR_CUSTOM'])){
			$baseColorCustom = $arBackParametrs['BASE_COLOR_CUSTOM'] = str_replace('#', '', $arBackParametrs['BASE_COLOR_CUSTOM']);
			if($arBackParametrs['THEME_SWITCHER'] === 'Y' && strlen($_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM'])){
				$baseColorCustom = $_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM'] = str_replace('#', '', $_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM']);
			}
		}

		$bGenerateAll = self::devMode || $bNeedGenerateAllThemes;
		$bGenerateCustom = $bGenerateAll || $bNeedGenerateCustomTheme || ($arBackParametrs['THEME_SWITCHER'] === 'Y' && $isCustomTheme && strlen($baseColorCustom) && $baseColorCustom != $lastGeneratedBaseColorCustom);
		if($arBaseColors && is_array($arBaseColors) && ($bGenerateAll || $bGenerateCustom)){
			if(!class_exists('lessc')){
				include_once 'lessc.inc.php';
			}
			$less = new lessc;
			try{
				foreach($arBaseColors as $colorCode => $arColor){
					if(($bCustom = ($colorCode == 'CUSTOM')) && $bGenerateCustom){
						if(strlen($baseColorCustom)){
							$less->setVariables(array('bcolor' => (strlen($baseColorCustom) ? '#'.$baseColorCustom : $arBaseColors[self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR']['DEFAULT']]['COLOR'])));
						}
					}
					elseif($bGenerateAll){
						$less->setVariables(array('bcolor' => $arColor['COLOR']));
					}

					if($bGenerateAll || ($bCustom && $bGenerateCustom)){
						if(defined('SITE_TEMPLATE_PATH')){
							$themeDirPath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/themes/'.$colorCode.($colorCode !== 'CUSTOM' ? '' : '_'.SITE_ID).'/';
							if(!is_dir($themeDirPath)) mkdir($themeDirPath, 0755, true);
							$output = $less->compileFile(__DIR__.'/../../css/colors.less', $themeDirPath.'colors.css');
							if($output && $bCustom){
								COption::SetOptionString(self::MODULE_ID, 'LastGeneratedBaseColorCustom', $baseColorCustom, '', SITE_ID);
							}
						}
					}
				}
			}
			catch(exception $e){
				echo 'Fatal error: '.$e->getMessage();
				die();
			}

			if($bNeedGenerateAllThemes){
				COption::SetOptionString(self::MODULE_ID, "NeedGenerateThemes", 'N', '', SITE_ID);
			}
			if($bNeedGenerateCustomTheme){
				COption::SetOptionString(self::MODULE_ID, "NeedGenerateCustomTheme", 'N', '', SITE_ID);
			}
		}
	}

	function start($siteID){
		return true;
	}

	public function sendAsproBIAction($action = 'unknown') {
		if(CModule::IncludeModule('main')){
			global $APPLICATION;

			$socket = fsockopen('bi.aspro.ru', 80, $errno, $errstr, 10);
			if($socket){
				$answer = '';
				$arData = json_encode(
					array(
						'client' => 'aspro',
						'install_date' => date('Y-m-d H:i:s'),
						'solution_code' => self::MODULE_ID,
						'ip' => ($_SERVER['HTTP_X_REAL_IP'] ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['SERVER_ADDR']),
						'http_host' => $_SERVER['HTTP_HOST'],
						'bitrix_version' => SM_VERSION,
						'bitrix_edition' => $APPLICATION->ConvertCharset(self::getBitrixEdition(), SITE_CHARSET, 'utf-8'),
						'bitrix_key_hash' => md5(CUpdateClient::GetLicenseKey()),
						'site_name' => $APPLICATION->ConvertCharset(COption::GetOptionString('main', 'site_name'), SITE_CHARSET, 'utf-8'),
						'site_url' => $APPLICATION->ConvertCharset(COption::GetOptionString('main', 'server_name'), SITE_CHARSET, 'utf-8'),
						'email_default' => $APPLICATION->ConvertCharset(COption::GetOptionString('main', 'email_from'), SITE_CHARSET, 'utf-8'),
						'action' => $action,
					)
				);

				fwrite($socket, "POST /rest/bitrix/installs HTTP/1.1\r\n");
				fwrite($socket, "Host: bi.aspro.ru\r\n");
				fwrite($socket, "Content-type: application/x-www-form-urlencoded\r\n");
				fwrite($socket, "Content-length:".strlen($arData)."\r\n");
				fwrite($socket, "Accept:*/*\r\n");
				fwrite($socket, "User-agent:Bitrix Installer\r\n");
				fwrite($socket, "Connection:Close\r\n");
				fwrite($socket, "\r\n");
				fwrite($socket, "$arData\r\n");
				fwrite($socket, "\r\n");

				while(!feof($socket)){
					$answer.= fgets($socket, 4096);
				}
			}
			fclose($socket);
		}
	}

	public function correctInstall(){
		if(CModule::IncludeModule('main')){
			if(COption::GetOptionString(self::MODULE_ID, 'WIZARD_DEMO_INSTALLED') == 'Y'){
				require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/wizard.php');
				@set_time_limit(0);
				if(!CWizardUtil::DeleteWizard(self::PARTNER_NAME.':'.self::SOLUTION_NAME)){
					if(!DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards/'.self::PARTNER_NAME.'/'.self::SOLUTION_NAME.'/')){
						self::removeDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards/'.self::PARTNER_NAME.'/'.self::SOLUTION_NAME.'/');
					}
				}

				UnRegisterModuleDependences('main', 'OnBeforeProlog', self::MODULE_ID, __CLASS__, 'correctInstall');
				COption::SetOptionString(self::MODULE_ID, 'WIZARD_DEMO_INSTALLED', 'N');
			}
		}
	}

	protected function getBitrixEdition(){
		$edition = 'UNKNOWN';

		if(CModule::IncludeModule('main')){
			include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/update_client.php');
			$arUpdateList = CUpdateClient::GetUpdatesList(($errorMessage = ''), 'ru', 'Y');
			if(array_key_exists('CLIENT', $arUpdateList) && $arUpdateList['CLIENT'][0]['@']['LICENSE']){
				$edition = $arUpdateList['CLIENT'][0]['@']['LICENSE'];
			}
		}

		return $edition;
	}

	protected function removeDirectory($dir){
		if($objs = glob($dir.'/*')){
			foreach($objs as $obj){
				if(is_dir($obj)){
					self::removeDirectory($obj);
				}
				else{
					if(!@unlink($obj)){
						if(chmod($obj, 0777)){
							@unlink($obj);
						}
					}
				}
			}
		}
		if(!@rmdir($dir)){
			if(chmod($dir, 0777)){
				@rmdir($dir);
			}
		}
	}

	function cacheElement($arOrder = array('SORT' => 'ASC'), $arrFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array(), $tag_cache = ''){
		if(!is_array($arOrder)){
			$arOrder = array('SORT' => 'ASC');
		}

		CModule::IncludeModule('iblock');

		$cache = new CPHPCache();
		$cache_time = 250000;
		$cache_path = 'aspro_cache_element';

		$cache_id = 'aspro_cache_element_'.serialize($arOrder).serialize($arrFilter).serialize($arGroupBy).serialize($arNavStartParams).serialize($arSelectFields);
		if(COption::GetOptionString('main', 'component_cache_on', 'Y') == 'Y' && $cache->InitCache($cache_time, $cache_id, $cache_path)){
			$res = $cache->GetVars();
			$arRes = $res['arRes'];
		}
		else{
			$rsRes = CIBlockElement::GetList($arOrder, $arrFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
			while($obj = $rsRes->GetNextElement()){
				$res = $obj->GetFields();
				$res['PROPERTIES'] = $obj->GetProperties();
				$arRes[$res['ID']] = $res;
			}
			if(COption::GetOptionString('main', 'component_cache_on', 'Y') == 'Y' && $cache_time > 0){
				$cache->StartDataCache( $cache_time, $cache_id, $cache_path );

				if(!empty($tag_cache)){
					global $CACHE_MANAGER;
					$CACHE_MANAGER->StartTagCache( $cache_path );
					$CACHE_MANAGER->RegisterTag( $tag_cache );
					$CACHE_MANAGER->EndTagCache();
				}

				$cache->EndDataCache(
					array(
						'arRes' => $arRes
					)
				);
			}
		}
		return $arRes;
	}

	function cacheSection($arOrder = array('SORT'=>'ASC'), $arrFilter = array(), $bincCount = false, $arSelect = array(), $single = false, $tag_cache = ''){
		if(!is_array($arOrder)){
			$arOrder = array('SORT' => 'ASC');
		}

		CModule::IncludeModule('iblock');

		$cache = new CPHPCache();
		$cache_time = 250000;
		$cache_path = 'aspro_cache_section';

		$cache_id = 'aspro_cache_section_'.serialize($arOrder).serialize($arrFilter).$bincCount.serialize($arSelect);
		if(COption::GetOptionString('main', 'component_cache_on', 'Y') == 'Y' && $cache->InitCache($cache_time, $cache_id, $cache_path)){
			$res = $cache->GetVars();
			$arRes = $res['arRes'];
		}
		else{
			$rsRes = CIBlockSection::GetList($arOrder, $arrFilter, $bincCount, $arSelect);
			if($single){
				$arRes = $rsRes->GetNext();
			}
			else{
				while($res = $rsRes->GetNext()){
					$arRes[$res['ID']] = $res;
				}
			}

			if(COption::GetOptionString('main', 'component_cache_on', 'Y') == 'Y' && $cache_time > 0){
				$cache->StartDataCache($cache_time, $cache_id, $cache_path);

				if(!empty($tag_cache)){
					global $CACHE_MANAGER;
					$CACHE_MANAGER->StartTagCache($cache_path);
					$CACHE_MANAGER->RegisterTag($tag_cache);
					$CACHE_MANAGER->EndTagCache();
				}

				$cache->EndDataCache(
					array(
						'arRes' => $arRes
					)
				);
			}
		}
		return $arRes;
	}

	function get_file_info($fileID){
		$file = CFile::GetFileArray($fileID);
		$pos = strrpos($file['FILE_NAME'], '.');
		$file['FILE_NAME'] = substr($file['FILE_NAME'], $pos);
		if(!$file['FILE_SIZE']){
			// bx bug in some version
			$file['FILE_SIZE'] = filesize($_SERVER['DOCUMENT_ROOT'].$file['SRC']);
		}
		$frm = explode('.', $file['FILE_NAME']);
		$frm = $frm[1];
		if($frm == 'doc' || $frm == 'docx'){
			$type = 'doc';
		}
		elseif($frm == 'xls' || $frm == 'xlsx'){
			$type = 'xls';
		}
		elseif($frm == 'jpg' || $frm == 'jpeg'){
			$type = 'jpg';
		}
		elseif($frm == 'png'){
			$type = 'png';
		}
		elseif($frm == 'ppt'){
			$type = 'ppt';
		}
		elseif($frm == 'tif'){
			$type = 'tif';
		}
		elseif($frm == 'txt'){
			$type = 'txt';
		}
		else{
			$type = 'pdf';
		}
		return $arr = array('TYPE' => $type, 'FILE_SIZE' => $file['FILE_SIZE'], 'SRC' => $file['SRC'], 'DESCRIPTION' => $file['DESCRIPTION'], 'ORIGINAL_NAME' => $file['ORIGINAL_NAME']);
	}

	function filesize_format($filesize){
		$formats = array(GetMessage('CT_NAME_b'), GetMessage('CT_NAME_KB'), GetMessage('CT_NAME_MB'), GetMessage('CT_NAME_GB'), GetMessage('CT_NAME_TB'));
		$format = 0;
		while($filesize > 1024 && count($formats) != ++$format){
			$filesize = round($filesize / 1024, 1);
		}
		$formats[] = GetMessage('CT_NAME_TB');
		return $filesize.' '.$formats[$format];
	}

	function getChilds($input, &$start = 0, $level = 0){
		static $arIblockItemsMD5 = array();

		if(!$level){
			$lastDepthLevel = 1;
			if($input && is_array($input)){
				foreach($input as $i => $arItem){
					if($arItem['DEPTH_LEVEL'] > $lastDepthLevel){
						if($i > 0){
							$input[$i - 1]['IS_PARENT'] = 1;
						}
					}
					$lastDepthLevel = $arItem['DEPTH_LEVEL'];
				}
			}
		}

		$childs = array();
		$count = count($input);
		for($i = $start; $i < $count; ++$i){
			$item = $input[$i];
			if(!isset($item)){
				continue;
			}
			if($level > $item['DEPTH_LEVEL'] - 1){
				break;
			}
			else{
				if(!empty($item['IS_PARENT'])){
					$i++;
					$item['CHILD'] = self::getChilds($input, $i, $level+1);
					$i--;
				}

				$childs[] = $item;
			}
		}
		$start = $i;

		if(is_array($childs)){
			foreach($childs as $j => $item){
				if($item['PARAMS']){
					$md5 = md5($item['TEXT'].$item['LINK'].$item['SELECTED'].$item['PERMISSION'].$item['ITEM_TYPE'].$item['IS_PARENT'].serialize($item['ADDITIONAL_LINKS']).serialize($item['PARAMS']));
					if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']])){
						if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level]) || ($item['DEPTH_LEVEL'] === 1 && !$level)){
							unset($childs[$j]);
							continue;
						}
					}
					if(!isset($arIblockItemsMD5[$md5])){
						$arIblockItemsMD5[$md5] = array($item['PARAMS']['DEPTH_LEVEL'] => array($level => true));
					}
					else{
						$arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level] = true;
					}
				}
			}
		}

		if(!$level){
			$arIblockItemsMD5 = array();
		}

		return $childs;
	}

	function sort_sections_by_field($arr, $name){
		$count = count($arr);
		for($i = 0; $i < $count; $i++){
			for($j = 0; $j < $count; $j++){
				if(strtoupper($arr[$i]['NAME']) < strtoupper($arr[$j]['NAME'])){
					$tmp = $arr[$i];
					$arr[$i] = $arr[$j];
					$arr[$j] = $tmp;
				}
			}
		}
		return $arr;
	}

	function getIBItems($prop, $checkNoImage){
		$arID = array();
		$arItems = array();
		$arAllItems = array();

		if($prop && is_array($prop)){
			foreach($prop as $reviewID){
				$arID[]=$reviewID;
			}
		}
		if($checkNoImage) $empty=false;
		$arItems = self::cacheElement(false, array('ID' => $arID, 'ACTIVE' => 'Y'));
		if($arItems && is_array($arItems)){
			foreach($arItems as $key => $arItem){
				if($checkNoImage){
					if(empty($arProject['PREVIEW_PICTURE'])){
						$empty=true;
					}
				}
				$arAllItems['ITEMS'][$key] = $arItem;
				if($arItem['DETAIL_PICTURE']) $arAllItems['ITEMS'][$key]['DETAIL'] = CFile::GetFileArray( $arItem['DETAIL_PICTURE'] );
				if($arItem['PREVIEW_PICTURE']) $arAllItems['ITEMS'][$key]['PREVIEW'] = CFile::ResizeImageGet( $arItem['PREVIEW_PICTURE'], array('width' => 425, 'height' => 330), BX_RESIZE_IMAGE_EXACT, true );
			}
		}
		if($checkNoImage) $arAllItems['NOIMAGE'] = 'YES';

		return $arAllItems;
	}

	function getSectionChilds($PSID, &$arSections, &$arSectionsByParentSectionID, &$arItemsBySectionID, &$aMenuLinksExt){
		if($arSections && is_array($arSections)){
			foreach($arSections as $arSection){
				if($arSection['IBLOCK_SECTION_ID'] == $PSID){
					$arItem = array($arSection['NAME'], $arSection['SECTION_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => $arSection['DEPTH_LEVEL']));
					$arItem[3]['IS_PARENT'] = (isset($arItemsBySectionID[$arSection['ID']]) || isset($arSectionsByParentSectionID[$arSection['ID']]) ? 1 : 0);
					$aMenuLinksExt[] = $arItem;
					if($arItem[3]['IS_PARENT']){
						// subsections
						self::getSectionChilds($arSection['ID'], $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt);
						// section elements
						if($arItemsBySectionID[$arSection['ID']] && is_array($arItemsBySectionID[$arSection['ID']])){
							foreach($arItemsBySectionID[$arSection['ID']] as $arItem){
								if(is_array($arItem['DETAIL_PAGE_URL'])){
									if(isset($arItem['CANONICAL_PAGE_URL'])){
										$arItem['DETAIL_PAGE_URL'] = $arItem['CANONICAL_PAGE_URL'];
									}
									else{
										$arItem['DETAIL_PAGE_URL'] = $arItem['DETAIL_PAGE_URL'][key($arItem['DETAIL_PAGE_URL'])];
									}
								}
								$aMenuLinksExt[] = array($arItem['NAME'], $arItem['DETAIL_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => ($arSection['DEPTH_LEVEL'] + 1), 'IS_ITEM' => 1));
							}
						}
					}
				}
			}
		}
	}

	function isChildsSelected($arChilds){
		if($arChilds && is_array($arChilds)){
			foreach($arChilds as $arChild){
				if($arChild['SELECTED']){
					return $arChild;
				}
			}
		}
		return false;
	}

	function SetJSOptions(){
		$arFrontParametrs = CStroy::GetFrontParametrsValues(SITE_ID);
		$tmp = $arFrontParametrs['DATE_FORMAT'];
		$DATE_MASK = ($tmp == 'DOT' ? 'd.m.y' : ($tmp == 'HYPHEN' ? 'd-m-y' : ($tmp == 'SPACE' ? 'd m y' : ($tmp == 'SLASH' ? 'd/m/y' : 'd:m:y'))));
		$VALIDATE_DATE_MASK = ($tmp == 'DOT' ? '^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}$' : ($tmp == 'HYPHEN' ? '^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}$' : ($tmp == 'SPACE' ? '^[0-9]{1,2} [0-9]{1,2} [0-9]{4}$' : ($tmp == 'SLASH' ? '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$' : '^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{4}$'))));
		$DATE_PLACEHOLDER = ($tmp == 'DOT' ? GetMessage('DATE_FORMAT_DOT') : ($tmp == 'HYPHEN' ? GetMessage('DATE_FORMAT_HYPHEN') : ($tmp == 'SPACE' ? GetMessage('DATE_FORMAT_SPACE') : ($tmp == 'SLASH' ? GetMessage('DATE_FORMAT_SLASH') : GetMessage('DATE_FORMAT_COLON')))));
		$DATETIME_MASK = ($tmp == 'DOT' ? 'd.m.y' : ($tmp == 'HYPHEN' ? 'd-m-y' : ($tmp == 'SPACE' ? 'd m y' : ($tmp == 'SLASH' ? 'd/m/y' : 'd:m:y')))).' h:s';
		$DATETIME_PLACEHOLDER = ($tmp == 'DOT' ? GetMessage('DATE_FORMAT_DOT') : ($tmp == 'HYPHEN' ? GetMessage('DATE_FORMAT_HYPHEN') : ($tmp == 'SPACE' ? GetMessage('DATE_FORMAT_SPACE') : ($tmp == 'SLASH' ? GetMessage('DATE_FORMAT_SLASH') : GetMessage('DATE_FORMAT_COLON'))))).' '.GetMessage('TIME_FORMAT_COLON');
		$VALIDATE_DATETIME_MASK = ($tmp == 'DOT' ? '^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'HYPHEN' ? '^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'SPACE' ? '^[0-9]{1,2} [0-9]{1,2} [0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'SLASH' ? '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : '^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$'))));
		?>
		<script type='text/javascript'>
		var arStroyOptions = ({
			'SITE_DIR' : '<?=SITE_DIR?>',
			'SITE_ID' : '<?=SITE_ID?>',
			'SITE_TEMPLATE_PATH' : '<?=SITE_TEMPLATE_PATH?>',
			'THEME' : ({
				'THEME_SWITCHER' : '<?=$arFrontParametrs['THEME_SWITCHER']?>',
				'BASE_COLOR' : '<?=$arFrontParametrs['BASE_COLOR']?>',
				'BASE_COLOR_CUSTOM' : '<?=$arFrontParametrs['BASE_COLOR_CUSTOM']?>',
				'TOP_MENU' : '<?=$arFrontParametrs['TOP_MENU']?>',
				'TOP_MENU_FIXED' : '<?=$arFrontParametrs['TOP_MENU_FIXED']?>',
				'COLORED_LOGO' : '<?=$arFrontParametrs['COLORED_LOGO']?>',
				'SIDE_MENU' : '<?=$arFrontParametrs['SIDE_MENU']?>',
				'SCROLLTOTOP_TYPE' : '<?=$arFrontParametrs['SCROLLTOTOP_TYPE']?>',
				'SCROLLTOTOP_POSITION' : '<?=$arFrontParametrs['SCROLLTOTOP_POSITION']?>',
				'USE_CAPTCHA_FORM' : '<?=$arFrontParametrs['USE_CAPTCHA_FORM']?>',
				'PHONE_MASK' : '<?=$arFrontParametrs['PHONE_MASK']?>',
				'VALIDATE_PHONE_MASK' : '<?=$arFrontParametrs['VALIDATE_PHONE_MASK']?>',
				'DATE_MASK' : '<?=$DATE_MASK?>',
				'DATE_PLACEHOLDER' : '<?=$DATE_PLACEHOLDER?>',
				'VALIDATE_DATE_MASK' : '<?=($VALIDATE_DATE_MASK)?>',
				'DATETIME_MASK' : '<?=$DATETIME_MASK?>',
				'DATETIME_PLACEHOLDER' : '<?=$DATETIME_PLACEHOLDER?>',
				'VALIDATE_DATETIME_MASK' : '<?=($VALIDATE_DATETIME_MASK)?>',
				'VALIDATE_FILE_EXT' : '<?=$arFrontParametrs['VALIDATE_FILE_EXT']?>',
				'SOCIAL_VK' : '<?=$arFrontParametrs['SOCIAL_VK']?>',
				'SOCIAL_FACEBOOK' : '<?=$arFrontParametrs['SOCIAL_FACEBOOK']?>',
				'SOCIAL_TWITTER' : '<?=$arFrontParametrs['SOCIAL_TWITTER']?>',
				'SOCIAL_YOUTUBE' : '<?=$arFrontParametrs['SOCIAL_YOUTUBE']?>',
				'SOCIAL_ODNOKLASSNIKI' : '<?=$arFrontParametrs['SOCIAL_ODNOKLASSNIKI']?>',
				'SOCIAL_GOOGLEPLUS' : '<?=$arFrontParametrs['SOCIAL_GOOGLEPLUS']?>',
				'BANNER_WIDTH' : '<?=$arFrontParametrs['BANNER_WIDTH']?>',
				'TEASERS_INDEX' : '<?=$arFrontParametrs['TEASERS_INDEX']?>',
				'CATALOG_INDEX' : '<?=$arFrontParametrs['CATALOG_INDEX']?>',
				'CATALOG_FAVORITES_INDEX' : '<?=$arFrontParametrs['CATALOG_FAVORITES_INDEX']?>',
				'BIGBANNER_ANIMATIONTYPE' : '<?=$arFrontParametrs['BIGBANNER_ANIMATIONTYPE']?>',
				'BIGBANNER_SLIDESSHOWSPEED' : '<?=$arFrontParametrs['BIGBANNER_SLIDESSHOWSPEED']?>',
				'BIGBANNER_ANIMATIONSPEED' : '<?=$arFrontParametrs['BIGBANNER_ANIMATIONSPEED']?>',
				'PARTNERSBANNER_SLIDESSHOWSPEED' : '<?=$arFrontParametrs['PARTNERSBANNER_SLIDESSHOWSPEED']?>',
				'PARTNERSBANNER_ANIMATIONSPEED' : '<?=$arFrontParametrs['PARTNERSBANNER_ANIMATIONSPEED']?>',
				'USE_YA_COUNTER' : '<?=$arFrontParametrs['USE_YA_COUNTER']?>',
				'YA_COUNTER_ID' : '<?=$arFrontParametrs['YA_COUNTER_ID']?>',
				'USE_FORMS_GOALS' : '<?=$arFrontParametrs['USE_FORMS_GOALS']?>',
				'USE_DEBUG_GOALS' : '<?=$arFrontParametrs['USE_DEBUG_GOALS']?>',
			})
		});
		</script>
		<?
	}

	function IsCompositeEnabled(){
		if(class_exists('CHTMLPagesCache')){
			if(method_exists('CHTMLPagesCache', 'GetOptions')){
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
					if(method_exists('CHTMLPagesCache', 'isOn')){
						if (CHTMLPagesCache::isOn()){
							if(isset($arHTMLCacheOptions['AUTO_COMPOSITE']) && $arHTMLCacheOptions['AUTO_COMPOSITE'] === 'Y'){
								return 'AUTO_COMPOSITE';
							}
							else{
								return 'COMPOSITE';
							}
						}
					}
					else{
						if($arHTMLCacheOptions['COMPOSITE'] === 'Y'){
							return 'COMPOSITE';
						}
					}
				}
			}
		}

		return false;
	}

	function EnableComposite($auto = false){
		if(class_exists('CHTMLPagesCache')){
			if(method_exists('CHTMLPagesCache', 'GetOptions')){
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
					$arHTMLCacheOptions['COMPOSITE'] = 'Y';
					$arHTMLCacheOptions['AUTO_UPDATE'] = 'Y'; // standart mode
					$arHTMLCacheOptions['AUTO_UPDATE_TTL'] = '0'; // no ttl delay
					$arHTMLCacheOptions['AUTO_COMPOSITE'] = ($auto ? 'Y' : 'N'); // auto composite mode
					CHTMLPagesCache::SetEnabled(true);
					CHTMLPagesCache::SetOptions($arHTMLCacheOptions);
					bx_accelerator_reset();
				}
			}
		}
	}

	function GetCurrentElementFilter(&$arVariables, &$arParams){
        $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'Y');
        if($arParams['CHECK_DATES'] == 'Y'){
            $arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
        }
        if($arVariables['ELEMENT_ID']){
            $arFilter['ID'] = $arVariables['ELEMENT_ID'];
        }
        elseif(strlen($arVariables['ELEMENT_CODE'])){
            $arFilter['CODE'] = $arVariables['ELEMENT_CODE'];
        }
		if($arVariables['SECTION_ID']){
			$arFilter['SECTION_ID'] = ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false);
		}
		if($arVariables['SECTION_CODE']){
			$arFilter['SECTION_CODE'] = ($arVariables['SECTION_CODE'] ? $arVariables['SECTION_CODE'] : false);
		}
        if(!$arFilter['SECTION_ID'] && !$arFilter['SECTION_CODE']){
            unset($arFilter['SECTION_GLOBAL_ACTIVE']);
        }
        return $arFilter;
    }

	function GetCurrentSectionFilter(&$arVariables, &$arParams){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if($arVariables['SECTION_ID']){
			$arFilter['ID'] = $arVariables['SECTION_ID'];
		}
		if(strlen($arVariables['SECTION_CODE'])){
			$arFilter['CODE'] = $arVariables['SECTION_CODE'];
		}
		if(!$arVariables['SECTION_ID'] && !strlen($arFilter['CODE'])){
			$arFilter['ID'] = 0; // if section not found
		}
		return $arFilter;
	}

	function GetCurrentSectionElementFilter(&$arVariables, &$arParams, $CurrentSectionID = false, $includeSubsection = 'N'){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => $includeSubsection);
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(!($arFilter['SECTION_ID'] = ($CurrentSectionID !== false ? $CurrentSectionID : ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false)))){
			if(strlen($arVariables['SECTION_CODE'])){
				$arFilter['SECTION_CODE'] = $arVariables['SECTION_CODE'];
			}
			else{
				unset($arFilter['SECTION_GLOBAL_ACTIVE']);
			}
		}
		if(strlen($arParams['FILTER_NAME'])){
			$GLOBALS[$arParams['FILTER_NAME']] = (array)$GLOBALS[$arParams['FILTER_NAME']];
			foreach($arUnsetFilterFields = array('SECTION_ID', 'SECTION_CODE', 'SECTION_ACTIVE', 'SECTION_GLOBAL_ACTIVE') as $filterUnsetField){
				foreach($GLOBALS[$arParams['FILTER_NAME']] as $filterField => $filterValue){
					if(($p = strpos($filterUnsetField, $filterField)) !== false && $p < 2){
						unset($GLOBALS[$arParams['FILTER_NAME']][$filterField]);
					}
				}
			}
			if($GLOBALS[$arParams['FILTER_NAME']]){
				$arFilter = array_merge($arFilter, $GLOBALS[$arParams['FILTER_NAME']]);
			}
		}
		return $arFilter;
	}

	function GetCurrentSectionSubSectionFilter(&$arVariables, &$arParams, $CurrentSectionID = false){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(!$arFilter['SECTION_ID'] = ($CurrentSectionID !== false ? $CurrentSectionID : ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false))){
			$arFilter['INCLUDE_SUBSECTIONS'] = 'N';array_merge($arFilter, array('INCLUDE_SUBSECTIONS' => 'N', 'DEPTH_LEVEL' => '1'));
			$arFilter['DEPTH_LEVEL'] = '1';
			unset($arFilter['GLOBAL_ACTIVE']);
		}
		return $arFilter;
	}

	function GetIBlockAllElementsFilter(&$arParams){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'Y');
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(strlen($arParams['FILTER_NAME']) && (array)$GLOBALS[$arParams['FILTER_NAME']]){
			$arFilter = array_merge($arFilter, (array)$GLOBALS[$arParams['FILTER_NAME']]);
		}
		return $arFilter;
	}

	function CheckSmartFilterSEF($arParams, $component){
		if($arParams['SEF_MODE'] === 'Y' && strlen($arParams['FILTER_URL_TEMPLATE']) && is_object($component)){
			$arVariables = $arDefaultUrlTemplates404 = $arDefaultVariableAliases404 = $arDefaultVariableAliases = array();
			$smartBase = ($arParams["SEF_URL_TEMPLATES"]["section"] ? $arParams["SEF_URL_TEMPLATES"]["section"] : "#SECTION_ID#/");
			$arParams["SEF_URL_TEMPLATES"]["smart_filter"] = $smartBase."filter/#SMART_FILTER_PATH#/apply/";
			$arComponentVariables = array("SECTION_ID", "SECTION_CODE", "ELEMENT_ID", "ELEMENT_CODE", "action");
			$engine = new CComponentEngine($component);
			$engine->addGreedyPart("#SECTION_CODE_PATH#");
			$engine->addGreedyPart("#SMART_FILTER_PATH#");
			$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
			$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
			$componentPage = $engine->guessComponentPath($arParams["SEF_FOLDER"], $arUrlTemplates, $arVariables);
			if($componentPage === 'smart_filter'){
				$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);
				CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
				return $arResult = array("FOLDER" => $arParams["SEF_FOLDER"], "URL_TEMPLATES" => $arUrlTemplates, "VARIABLES" => $arVariables, "ALIASES" => $arVariableAliases);
			}
		}

		return false;
	}

	function AddMeta($arParams = array()){
		self::$arMetaParams = array_merge((array)self::$arMetaParams, (array)$arParams);
	}

	function SetMeta(){
		global $APPLICATION, $arSite;

		$PageH1 = $APPLICATION->GetTitle();
		$PageMetaTitleBrowser = $APPLICATION->GetPageProperty('title');
		$DirMetaTitleBrowser = $APPLICATION->GetDirProperty('title');
		$PageMetaDescription = $APPLICATION->GetPageProperty('description');
		$DirMetaDescription = $APPLICATION->GetDirProperty('description');

		// set title
		if(!CSite::inDir(SITE_DIR.'index.php')){
			if(!strlen($PageMetaTitleBrowser)){
				if(!strlen($DirMetaTitleBrowser)){
					$PageMetaTitleBrowser = $PageH1.((strlen($PageH1) && strlen($arSite['SITE_NAME'])) ? ' - ' : '' ).$arSite['SITE_NAME'];
					$APPLICATION->SetPageProperty('title', $PageMetaTitleBrowser);
				}
			}
		}
		else{
			if(!strlen($PageMetaTitleBrowser)){
				if(!strlen($DirMetaTitleBrowser)){
					$PageMetaTitleBrowser = $arSite['SITE_NAME'].((strlen($arSite['SITE_NAME']) && strlen($PageH1)) ? ' - ' : '' ).$PageH1;
					$APPLICATION->SetPageProperty('title', $PageMetaTitleBrowser);
				}
			}
		}

		// check Open Graph required meta properties
		if(!strlen(self::$arMetaParams['og:title'])){
			self::$arMetaParams['og:title'] = $PageMetaTitleBrowser;
		}
		if(!strlen(self::$arMetaParams['og:type'])){
			self::$arMetaParams['og:type'] = 'article';
		}
		if(!strlen(self::$arMetaParams['og:image'])){
			self::$arMetaParams['og:image'] = SITE_DIR.'logo.png'; // site logo
		}
		if(!strlen(self::$arMetaParams['og:url'])){
			self::$arMetaParams['og:url'] = $_SERVER['REQUEST_URI'];
		}
		if(!strlen(self::$arMetaParams['og:description'])){
			self::$arMetaParams['og:description'] = (strlen($PageMetaDescription) ? $PageMetaDescription : $DirMetaDescription);
		}

		foreach(self::$arMetaParams as $metaName => $metaValue){
			if(strlen($metaValue = strip_tags($metaValue))){
				$APPLICATION->AddHeadString('<meta property="'.$metaName.'" content="'.$metaValue.'" />', true);
				if($metaName === 'og:image'){
					$APPLICATION->AddHeadString('<link rel="image_src" href="'.$metaValue.'"  />', true);
				}
			}
		}
	}

	function CheckAdditionalChainInMultiLevel(&$arResult, &$arParams, &$arElement){
		global $APPLICATION;
		$APPLICATION->arAdditionalChain = false;
		if($arParams['INCLUDE_IBLOCK_INTO_CHAIN'] == 'Y' && isset(CCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME'])){
			$APPLICATION->AddChainItem(CCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME'], $arElement['~LIST_PAGE_URL']);
		}
		if($arParams['ADD_SECTIONS_CHAIN'] == 'Y'){
			if($arSection = CCache::CIBlockSection_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arElement['IBLOCK_ID']), 'MULTI' => 'N')), self::GetCurrentSectionFilter($arResult['VARIABLES'], $arParams), false, array('ID', 'NAME'))){
				$rsPath = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arSection['ID']);
				$rsPath->SetUrlTemplates('', $arParams['SECTION_URL']);
				while($arPath = $rsPath->GetNext()){
					$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $arPath['ID']);
					$arPath['IPROPERTY_VALUES'] = $ipropValues->getValues();
					$arSection['PATH'][] = $arPath;
					$arSection['SECTION_URL'] = $arPath['~SECTION_PAGE_URL'];
				}

				foreach($arSection['PATH'] as $arPath){
					if($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != ''){
						$APPLICATION->AddChainItem($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $arPath['~SECTION_PAGE_URL']);
					}
					else{
						$APPLICATION->AddChainItem($arPath['NAME'], $arPath['~SECTION_PAGE_URL']);
					}
				}
			}
		}
		if($arParams['ADD_ELEMENT_CHAIN'] == 'Y'){
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arParams['IBLOCK_ID'], $arElement['ID']);
			$arElement['IPROPERTY_VALUES'] = $ipropValues->getValues();
			if($arElement['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''){
				$APPLICATION->AddChainItem($arElement['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']);
			}
			else{
				$APPLICATION->AddChainItem($arElement['NAME']);
			}
		}
	}

	function CheckDetailPageUrlInMultilevel(&$arResult){
		if($arResult['ITEMS']){
			$arItemsIDs = $arItems = array();
			$CurrentSectionID = false;
			foreach($arResult['ITEMS'] as $arItem){
				$arItemsIDs[] = $arItem['ID'];
			}
			$arItems = CCache::CIBLockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arItemsIDs), false, false, array('ID', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL'));
			if($arResult['SECTION']['PATH']){
				for($i = count($arResult['SECTION']['PATH']) - 1; $i >= 0; --$i){
					if(CSite::InDir($arResult['SECTION']['PATH'][$i]['SECTION_PAGE_URL'])){
						$CurrentSectionID = $arResult['SECTION']['PATH'][$i]['ID'];
						break;
					}
				}
			}
			foreach($arResult['ITEMS'] as $i => $arItem){
				if(is_array($arItems[$arItem['ID']]['DETAIL_PAGE_URL'])){
					if($arItems[$arItem['ID']]['DETAIL_PAGE_URL'][$CurrentSectionID]){
						$arResult['ITEMS'][$i]['DETAIL_PAGE_URL'] = $arItems[$arItem['ID']]['DETAIL_PAGE_URL'][$CurrentSectionID];
					}
				}
				if(is_array($arItems[$arItem['ID']]['IBLOCK_SECTION_ID'])){
					$arResult['ITEMS'][$i]['IBLOCK_SECTION_ID'] = $CurrentSectionID;
				}
			}
		}
	}

	function FormatPriceShema($strPrice = ''){
		if(strlen($strPrice = trim($strPrice))){
			$arCur = array(
				'$' => 'USD',
				'�' => 'EUR',
				GetMessage('STROY_CUR_RUB1') => 'RUB',
				GetMessage('STROY_CUR_RUB2') => 'RUB',
				GetMessage('STROY_CUR_UAH1') => 'UAH',
				GetMessage('STROY_CUR_UAH2') => 'UAH',
				GetMessage('STROY_CUR_RUB3') => 'RUB',
				GetMessage('STROY_CUR_RUB4') => 'RUB',
				GetMessage('STROY_CUR_RUB5') => 'RUB',
				GetMessage('STROY_CUR_RUB6') => 'RUB',
				GetMessage('STROY_CUR_RUB3') => 'RUB',
				GetMessage('STROY_CUR_UAH3') => 'UAH',
				GetMessage('STROY_CUR_RUB5') => 'RUB',
				GetMessage('STROY_CUR_UAH6') => 'UAH',
			);

			foreach($arCur as $curStr => $curCode){
				if(strpos($strPrice, $curStr) !== false){
					$priceVal = str_replace($curStr, '', $strPrice);
					return str_replace(array($curStr, $priceVal), array('<span class="currency" itemprop="priceCurrency" content="'.$curCode.'">'.$curStr.'</span>', '<span itemprop="price" content="'.$priceVal.'">'.$priceVal.'</span>'), $strPrice);
				}
			}
		}
		return $strPrice;
	}

	function GetBannerStyle($bannerwidth, $topmenu){
        $style = "";

        if($bannerwidth == "WIDE"){
            $style = ".maxwidth-banner{max-width: 1480px;}";
        }
        elseif($bannerwidth == "MIDDLE"){
            $style = ".maxwidth-banner{max-width: 1280px;}";
        }
        elseif($bannerwidth == "NARROW"){
            $style = ".maxwidth-banner{max-width: 1140px; padding: 0 15px; margin-top: 0px !important;}";
			if($topmenu !== 'LIGHT'){
				$style .= ".banners-big{margin-top:20px;}";
			}
        }
        else{
            $style = ".maxwidth-banner{max-width: auto;}";
        }

        return "<style>".$style."</style>";
    }

	function GetDirMenuParametrs($dir){
		if(strlen($dir)){
			$file = str_replace('//', '/', $dir.'/.section.php');
			if(file_exists($file)){
				@include($file);
				return $arDirProperties;
			}
		}

		return false;
	}

	function goto404Page(){
		global $APPLICATION;

		if($_SESSION['SESS_INCLUDE_AREAS']){
			echo '</div>';
		}
		echo '</div>';
		$APPLICATION->IncludeFile(SITE_DIR.'404.php', array(), array('MODE' => 'html'));
		die();
	}

	function checkRestartBuffer(){
		global $APPLICATION;
		static $bRestarted;

		if($bRestarted){
			die();
		}

		if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y')){
			$APPLICATION->RestartBuffer();
			$bRestarted = true;
		}
	}

	function UpdateFormEvent(&$arFields){
		// AddMessage2Log('UpdateFormEvent', '');
		if($arFields['ID'] && $arFields['IBLOCK_ID']){
			// find aspro form event for this iblock
			$arEventIDs = array('ASPRO_SEND_FORM_'.$arFields['IBLOCK_ID'], 'ASPRO_SEND_FORM_ADMIN_'.$arFields['IBLOCK_ID']);
			$arLangIDs = array('ru', 'en');
			static $arEvents;
			if($arEvents == NULL){
				foreach($arEventIDs as $EVENT_ID){
					foreach($arLangIDs as $LANG_ID){
						$resEvents = CEventType::GetByID($EVENT_ID, $LANG_ID);
						$arEvents[$EVENT_ID][$LANG_ID] = $resEvents->Fetch();
					}
				}
			}
			if($arEventIDs){
				foreach($arEventIDs as $EVENT_ID){
					foreach($arLangIDs as $LANG_ID){
						if($arEvent = &$arEvents[$EVENT_ID][$LANG_ID]){
							if(strpos($arEvent['DESCRIPTION'], $arFields['NAME'].': #'.$arFields['CODE'].'#') === false){
								$arEvent['DESCRIPTION'] = str_replace('#'.$arFields['CODE'].'#', '-', $arEvent['DESCRIPTION']);
								$arEvent['DESCRIPTION'] .= $arFields['NAME'].': #'.$arFields['CODE']."#\n";
								CEventType::Update(array('ID' => $arEvent['ID']), $arEvent);
							}
						}
					}
				}
			}
		}
	}

	static function ShowAdminOptions($sid){
		CJSCore::Init(array("jquery"));
		CAjax::Init();
		$sid = strtoupper($sid);
		$right = $GLOBALS['APPLICATION']->GetGroupRight(self::MODULE_ID);
		?>
		<script src="/bitrix/js/<?=self::MODULE_ID?>/script.js" type="text/javascript"></script>
		<link href="/bitrix/css/<?=self::MODULE_ID?>/style.css" type="text/css" rel="stylesheet" />
		<div id="aspro_admin_area" data-sid="<?=$sid?>">
			<?if(isset(self::$arParametrsList[$sid])):?>
				<?
				$arTabs = $arSites = $arSitesIDs = array();

				if($arSitesIDs = self::GetSites()){
					$dbRes = CSite::GetList($by = 'id', $sort = 'asc', array('ACTIVE' => 'Y'));
					while($arSite = $dbRes->Fetch()){
						if(in_array($arSite['LID'], $arSitesIDs)){
							$arSites[$arSite['LID']] = $arSite;
						}
					}
				}

				if($arSites){
					foreach($arSites as $siteID => $arSite){
						$arTabs[] = array(
							'DIV' => 'edit'.$siteID,
							'TAB' => GetMessage('PRIME_OPTIONS_SITE_TITLE', array('#SITE_NAME#' => $arSite['NAME'], '#SITE_ID#' => $siteID)),
							'ICON' => 'settings',
							'TITLE' => self::$arParametrsList[$sid]['TAB_TITLE'],
							'PAGE_TYPE' => 'site_settings',
							'SITE_ID' => $siteID,
						);
					}
				}
				else{
					CAdminMessage::ShowMessage(GetMessage('PRIME_SITES_NOT_FOUND'));
				}

				if($arTabs){
					$tabControl = new CAdminTabControl('tabControl', $arTabs);
					$tabControl->Begin();
					?>
					<form method="post" action="<?=$GLOBALS['APPLICATION']->GetCurPage()?>?sid=<?=strtolower($sid)?>&amp;lang=<?=LANGUAGE_ID?>">
						<?=bitrix_sessid_post();?>
						<?foreach($arTabs as $arTab):?>
							<?$siteID = $arTab['SITE_ID'];?>
							<?$tabControl->BeginNextTab();?>
							<?self::_ShowAdminOptions(array(self::$arParametrsList[$sid]), $siteID);?>
						<?endforeach;?>
						<?$tabControl->Buttons();?>
						<?if($right > 'R'):?>
							<input type="submit" name="Apply" class="submit-btn" value="<?=GetMessage('PRIME_OPTIONS_APPLY_TITLE')?>" title="<?=GetMessage('PRIME_OPTIONS_APPLY_TITLE')?>">
							<?if(strlen($_REQUEST['back_url_settings'])):?>
								<input type="button" name="Cancel" value="<?=GetMessage('PRIME_OPTIONS_CANCEL_TITLE')?>" title="<?=GetMessage('PRIME_OPTIONS_CANCEL_TITLE')?>" onclick="window.location='<?=htmlspecialchars(CUtil::addslashes($_REQUEST['back_url_settings']))?>'">
								<input type="hidden" name="back_url_settings" value="<?=htmlspecialchars($_REQUEST['back_url_settings'])?>">
							<?endif;?>
						<?endif;?>
					</form>
					<?$tabControl->End();?>
					<?
				}
				?>
			<?else:?>
				<?LocalRedirect($GLOBALS['APPLICATION']->GetCurPage(false).'?sid='.strtolower(reset(array_keys(self::$arParametrsList))));?>
			<?endif;?>
		</div>
		<?
	}

	static protected function _ShowAdminOptions($arOptions, $siteID = false){
		static $level, $bTdTagOpened, $arAdminOptions;

		if(!isset($arAdminOptions[$siteID])){
			$arAdminOptions[$siteID] = self::GetAdminOptionsValues($siteID);
		}

		if(!isset($level)){
			$level = 0;
		}
		else{
			++$level;
		}

		if($arOptions && is_array($arOptions)){
			foreach($arOptions as $optionCode => $arOption){
				if(strlen($optionCode) && $arOption && is_array($arOption) && isset($arOption['TYPE']) && strlen($arOption['TYPE'])){
					if($arOption['TYPE'] === 'page'){
						$GLOBALS['APPLICATION']->SetTitle($arOption['TITLE']);
					}
					elseif($arOption['TYPE'] === 'section'){
						if($bTdTagOpened)
							$bTdTagOpened = !print('</tr></td>');
						?>
						<tr class="heading"><td><?=$arOption['TITLE']?></td></tr>
						<?
					}
					else{
						if($arOption['TYPE'] === 'note'){
							if(!$bTdTagOpened)
								$bTdTagOpened = print('<tr><td>');
							$align = strlen($arOption['ALIGN']) ? (in_array($arOption['ALIGN'], array('left', 'right', 'center')) ? $arOption['ALIGN'] : 'center') : 'center';
							?>
							<div class="aspro-admin-row">
								<div class="aspro-admin-col12">
									<?=BeginNote('align="'.$align.'"');?>
									<?=$arOption['TITLE']?>
									<?=EndNote();?>
								</div>
							</div>
							<?
						}
						else{
							$arOption['MULTIPLE'] = isset($arOption['MULTIPLE']) ? ($arOption['MULTIPLE'] === 'Y' ? 'Y' : 'N') : 'N';

							$optionType = $arOption['TYPE'];
							$optionTitle = htmlspecialcharsbx($arOption['TITLE']);
							$optionName = $optionCode.'_'.$siteID;
							$optionList = $arOption['LIST'];
							$optionVal = $arAdminOptions[$siteID][$optionCode];
							$optionDefault = $arOption['DEFAULT'];

							$optionSubscription = isset($arOption['SUBSCRIPTION']) ? ((is_string($arOption['SUBSCRIPTION']) ? (strlen($arOption['SUBSCRIPTION']) ? $arOption['SUBSCRIPTION'] : '') : (is_array($arOption['SUBSCRIPTION']) ? 'vail' : ''))) : '';
							$optionSup = isset($arOption['SUP']) ? $arOption['SUP'] : '';
							$optionDisabled = isset($arOption['DISABLED']) ? ($arOption['DISABLED'] === 'Y' ? 'disabled' : '') : '';

							$optionCols = $arOption["COLS"];
							$optionRows = $arOption["ROWS"];
							$optionChecked = $optionVal == "Y" ? "checked" : "";

							if(!$bTdTagOpened)
								$bTdTagOpened = print('<tr><td>');
							?>
							<?if($optionCode === 'TEST'):?>
							<?else:?>
								<div class="aspro-admin-row">
									<?if($optionType === 'html'):?>
										<div class="aspro-admin-col12" data-optioncode="<?=$optionCode?>">
											<div class="aspro-admin-option-html" <?=$optionDisabled?>><?=htmlspecialchars_decode($optionTitle)?></div>
										</div>
									<?else:?>
										<div class="aspro-admin-col6 aspro-admin-option-title" data-optioncode="<?=$optionCode?>">
											<?if($optionType == "checkbox"):?>
												<label for="<?=$optionName?>"><?=$optionTitle?></label>
											<?else:?>
												<?=$optionTitle?>
											<?endif;?>
											<?if(strlen($optionSup)):?>
												<sup><?=$optionSup?></sup>
											<?endif;?>
										</div>
										<div class="aspro-admin-col6 aspro-admin-option-value" data-optioncode="<?=$optionCode?>">
											<?if($optionType === 'radio'):?>
												<?$optionList = (array)$optionList;?>
												<div class="aspro-admin-option-radio">
													<?foreach($optionList as $itemValue => $arItem):?>
														<?$itemTitle = htmlspecialcharsbx(is_array($arItem) ? $arItem['TITLE'] : $arItem);?>
														<?$checked = ($itemValue == $optionVal ? 'checked' : '');?>
														<input type="radio" name="<?=$optionName?>" value="<?=$itemValue?>" <?=$checked?> <?=$optionDisabled?>><?=$itemTitle?><br />
													<?endforeach;?>
												</div>
											<?elseif($optionType === 'select'):?>
												<div class="aspro-admin-option-select">
													<?$optionList = (array)$optionList;?>
													<?if($arOption['MULTIPLE'] === 'Y'):?>
														<?$optionVal = (array)$optionVal;?>
														<select name="<?=$optionName?>" <?=$optionController?> <?=$optionDisabled?>>
															<?foreach($optionList as $itemValue => $arItem):?>
																<?$itemTitle = htmlspecialcharsbx(is_array($arItem) ? $arItem['TITLE'] : $arItem);?>
																<?$selected = (in_array($itemValue, $optionVal) ? 'selected' : '');?>
																<option value="<?=$itemValue?>" <?=$selected?>><?=$itemTitle?></option>
															<?endforeach;?>
														</select>
													<?else:?>
														<select name="<?=$optionName?>" <?=$optionController?> <?=$optionDisabled?>>
															<?foreach($optionList as $itemValue => $arItem):?>
																<?$itemTitle = htmlspecialcharsbx(is_array($arItem) ? $arItem['TITLE'] : $arItem);?>
																<?$selected = ($itemValue == $optionVal ? 'selected' : '');?>
																<option value="<?=$itemValue?>" <?=$selected?>><?=$itemTitle?></option>
															<?endforeach;?>
														</select>
													<?endif;?>
												</div>
											<?elseif($optionType === 'text' || $optionType === 'password'):?>
												<div class="aspro-admin-option-text">
													<?$optionMaxLength = (isset($arOption['MAX_LENGTH']) && is_int($arOption['MAX_LENGTH']) && $arOption['MAX_LENGTH'] > 0 ? 'maxlength="'.$arOption['MAX_LENGTH'].'"' : '');?>
													<input type="<?=$optionType?>" name="<?=$optionName?>" value="<?=$optionVal?>" <?=$optionMaxLength?> <?=$optionDisabled?> autocomplete="off">
												</div>
											<?elseif($optionType === 'file'):?>
												<?
												$arOption['MULTIPLE'] = 'N';
												self::_ShowFileAdminOption($optionCode, $arOption, $optionVal);
												?>
											<?elseif($optionType == "checkbox"):?>
												<input type="checkbox" <?=$optionController?> id="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" value="Y" <?=$optionChecked?> <?=$optionDisabled?> <?=(strlen($optionDefault) ? $optionDefault : "")?>>
											<?elseif($optionType == "textarea"):?>
												<textarea <?=$optionController?> <?=$optionDisabled?> rows="<?=$optionRows?>" cols="<?=$optionCols?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>"><?=htmlspecialcharsbx($optionVal)?></textarea>
											<?endif;?>
											<?if(strlen($optionSubscription)):?>
												<div class="aspro-admin-option-subscription"><?=$optionSubscription?></div>
											<?endif;?>
										</div>
									<?endif;?>
								</div>
							<?endif;?>
							<?
						}
					}
					if(isset($arOption['OPTIONS']) && is_array($arOption['OPTIONS'])){
						self::_ShowAdminOptions($arOption['OPTIONS'], $siteID);
					}
				}
			}
		}

		if(!--$level){
			if($bTdTagOpened)
				$bTdTagOpened = !print('</tr></td>');
		};
	}

	static protected function _ShowFileAdminOption($name, $arOption, $values){
		global $bCopy, $historyId;

		if(!is_array($values)){
			$values = array($values);
		}

		if($bCopy || empty($values)){
			$values = array('n0' => 0);
		}

		$optionWidth = $arOption['WIDTH'] ? $arOption['WIDTH'] : 200;
		$optionHeight = $arOption['HEIGHT'] ? $arOption['HEIGHT'] : 100;

		if($arOption['MULTIPLE'] == 'N'){
			foreach($values as $key => $val){
				if(is_array($val)){
					$file_id = $val['VALUE'];
				}
				else{
					$file_id = $val;
				}
				if($historyId > 0){
					echo CFileInput::Show($name.'['.$key.']', $file_id,
						array(
							'IMAGE' => $arOption['IMAGE'],
							'PATH' => 'Y',
							'FILE_SIZE' => 'Y',
							'DIMENSIONS' => 'Y',
							'IMAGE_POPUP' => 'Y',
							'MAX_SIZE' => array(
								'W' => $optionWidth,
								'H' => $optionHeight,
							),
						)
					);
				}
				else{
					echo CFileInput::Show($name.'['.$key.']', $file_id,
						array(
							'IMAGE' => $arOption['IMAGE'],
							'PATH' => 'Y',
							'FILE_SIZE' => 'Y',
							'DIMENSIONS' => 'Y',
							'IMAGE_POPUP' => 'Y',
							'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
							),
						),
						array(
							'upload' => true,
							'medialib' => true,
							'file_dialog' => true,
							'cloud' => true,
							'del' => true,
							'description' => $arOption['WITH_DESCRIPTION'] == 'Y',
						)
					);
				}
				break;
			}
		}
		else{
			$inputName = array();
			foreach($values as $key => $val){
				if(is_array($val)){
					$inputName[$name.'['.$key.']'] = $val['VALUE'];
				}
				else{
					$inputName[$name.'['.$key.']'] = $val;
				}
			}
			if($historyId > 0){
				echo CFileInput::ShowMultiple($inputName, $name.'[n#IND#]',
					array(
						'IMAGE' => $arOption['IMAGE'],
						'PATH' => 'Y',
						'FILE_SIZE' => 'Y',
						'DIMENSIONS' => 'Y',
						'IMAGE_POPUP' => 'Y',
						'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
						),
					),
				false);
			}
			else{
				echo CFileInput::ShowMultiple($inputName, $name.'[n#IND#]',
					array(
						'IMAGE' => $arOption['IMAGE'],
						'PATH' => 'Y',
						'FILE_SIZE' => 'Y',
						'DIMENSIONS' => 'Y',
						'IMAGE_POPUP' => 'Y',
						'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
						),
					),
				false,
					array(
						'upload' => true,
						'medialib' => true,
						'file_dialog' => true,
						'cloud' => true,
						'del' => true,
						'description' => $arOption['WITH_DESCRIPTION'] == 'Y',
					)
				);
			}
		}
	}

	static function SaveAdminOptions(){
		$right = $GLOBALS['APPLICATION']->GetGroupRight(self::MODULE_ID);
		if($_SERVER['REQUEST_METHOD'] == "POST" && strlen($_REQUEST['Apply'].$_REQUEST['Update'].$_REQUEST['RestoreDefaults']) && $right >= "W" && check_bitrix_sessid()){
			if(strlen($_REQUEST['RestoreDefaults'])){
				COption::RemoveOption(self::MODULE_ID);
				$GLOBALS['APPLICATION']->DelGroupRight(self::MODULE_ID);
			}
			else{
				$sid = $_REQUEST['sid'];
				if(isset($sid) && strlen($sid)){
					$sid = strtoupper($sid);
					if(isset(self::$arParametrsList[$sid])){
						if($arSitesIDs = self::GetSites()){
							foreach($arSitesIDs as $siteID){
								self::_SaveOptionsValues(self::$arParametrsList[$sid], $siteID);
							}
						}
					}
				}
			}

			if(self::IsCompositeEnabled()){
				$obCache = new CPHPCache();
				$obCache->CleanDir('', 'html_pages');
				self::EnableComposite();
			}

			//echo '<pre>';print_r($_REQUEST);echo '</pre>';
			//die();

			//$GLOBALS['APPLICATION']->RestartBuffer();
		}
	}

	protected static function _SaveOptionsValues($arParametrsList, $siteID){
		static $arAdminOptions;

		if(!isset($arAdminOptions[$siteID])){
			$arAdminOptions[$siteID] = self::GetAdminOptionsValues($siteID);
		}

		if(is_array($arParametrsList)){
			foreach($arParametrsList as $optionCode => $arOption){
				if(isset($arOption['TYPE']) && strlen($optionCode)){
					if(isset($arOption['DEFAULT']) && !in_array($arOption['TYPE'], array('page', 'tab', 'section', 'html'))){
						$bMultiple = (isset($arOption['MULTIPLE']) && $arOption['MULTIPLE'] === 'Y');
						$optionAdminValue = $arAdminOptions[$siteID][$optionCode];
						$newOptionValue = $_REQUEST[$optionCode.'_'.$siteID];

						if($bMultiple){
							$newOptionValue = (array)$newOptionValue;
						}

						if($arOption['TYPE'] === 'checkbox'){
							if(!isset($newOptionValue)){
								$newOptionValue = 'N';
							}
							else{
								$newOptionValue = ($newOptionValue === 'Y' ? 'Y' : 'N');
							}
						}

						echo $optionCode.' '.$newOptionValue.'<br />';
						COption::SetOptionString(self::MODULE_ID, $optionCode, (is_array($newOptionValue) ? serialize($newOptionValue) : $newOptionValue), '', $siteID);
					}

					if(isset($arOption['OPTIONS']) && is_array($arOption['OPTIONS'])){
						self::_SaveOptionsValues($arOption['OPTIONS'], $siteID);
					}
				}
			}
		}

	}

	function DoIBlockAfterSave($arFields){
		static $arPropCache = array();
		static $arPropArray=array();
		$codeProp="TYPE_BUILDINGS";

		if(!array_key_exists($arFields["IBLOCK_ID"], $arPropCache)){
			//Check for TYPE_BUILDINGS property
			$rsProperty = CIBlockProperty::GetByID($codeProp, $arFields["IBLOCK_ID"]);
			$arProperty = $rsProperty->Fetch();
			if($arProperty){
				$arPropCache[$arFields["IBLOCK_ID"]] = $arProperty["ID"];
				$arPropArray[$codeProp]=$arProperty["ID"];
			}else{
				if(!$arPropCache[$arFields["IBLOCK_ID"]])
					$arPropCache[$arFields["IBLOCK_ID"]] = false;
			}
		}


		if($arPropCache[$arFields["IBLOCK_ID"]]){
			if($arPropArray[$codeProp]){
				$arAllSections=array();
				$sectionID=($arFields["IBLOCK_SECTION"] ? $arFields["IBLOCK_SECTION"] : 0);
				if(!$sectionID){
					$arElement=CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>$arFields["IBLOCK_ID"], "ID" => $arFields["ID"]), false, false, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID"))->Fetch();
					if($arElement["IBLOCK_SECTION_ID"])
						$sectionID=$arElement["IBLOCK_SECTION_ID"];
				}

				if($sectionID){
					$rsSections = CIBlockSection::GetList(Array(), Array("IBLOCK_ID"=>$arFields["IBLOCK_ID"], "ID" => $sectionID));
					while($arSection=$rsSections->Fetch()){
						if($arSection["DEPTH_LEVEL"]>1){
							$arParentSection = CIBlockSection::GetList(Array(), Array("IBLOCK_ID"=>$arSection["IBLOCK_ID"], "<=LEFT_BORDER" => $arSection["LEFT_MARGIN"], ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1), false, array("ID", "NAME", "SORT", "XML_ID" ))->Fetch();
							$arAllSections[$arParentSection["ID"]]["ID"]=$arParentSection["ID"];
							$arAllSections[$arParentSection["ID"]]["NAME"]=$arParentSection["NAME"];
							$arAllSections[$arParentSection["ID"]]["XML_ID"]=$arParentSection["XML_ID"];
							$arAllSections[$arParentSection["ID"]]["SORT"]=$arParentSection["SORT"];
						}else{
							$arAllSections[$arSection["ID"]]["ID"]=$arSection["ID"];
							$arAllSections[$arSection["ID"]]["NAME"]=$arSection["NAME"];
							$arAllSections[$arSection["ID"]]["XML_ID"]=$arSection["XML_ID"];
							$arAllSections[$arSection["ID"]]["SORT"]=$arSection["SORT"];
						}
					}
				}
				if($arAllSections){
					$arProps=array();
					foreach($arAllSections as $arItem){
						$arPropItem = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arFields["IBLOCK_ID"], "CODE"=>$codeProp, "XML_ID" => $arItem["ID"]))->Fetch();
						if($arPropItem){
							$ibpenum = new CIBlockPropertyEnum;
							$ibpenum->Update($arPropItem["ID"], Array('SORT'=>$arItem["SORT"]));
							$arProps[]=$arPropItem["ID"];
						}else{
							$arAddFields=array('PROPERTY_ID'=>$arPropArray[$codeProp], 'VALUE'=>$arItem["NAME"], 'SORT'=>$arItem["SORT"], 'XML_ID' => $arItem["ID"]);
							$ibpenum = new CIBlockPropertyEnum;
							$id=$ibpenum->Add($arAddFields);
							$arProps[]=$id;
						}
					}
				}
				if($arAllSections){
					CIBlockElement::SetPropertyValuesEx(
						$arFields["ID"],
						$arFields["IBLOCK_ID"],
						array(
							$codeProp => $arProps,
						)
					);
				}else{
					CIBlockElement::SetPropertyValuesEx(
						$arFields["ID"],
						$arFields["IBLOCK_ID"],
						array(
							$codeProp => "",
						)
					);
				}
				if(class_exists('\Bitrix\Iblock\PropertyIndex\Manager')){
					\Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex($arFields["IBLOCK_ID"], $arFields["ID"]);
				}
			}
		}
	}

	function showSolutionIcon($url){
		// $IconsDir = '/bitrix/templates/aspro-stroy/images/icons';
		$IconsDir = $url;
		$IconsServerDir = $_SERVER["DOCUMENT_ROOT"].$IconsDir;
		$IconsArr = scandir($IconsServerDir);
		if($IconsArr){
			echo '<div class="row">';
			$lineCount = 0;//������� ��� ������
			for($i = 0; $i != count($IconsArr); $i++){
				if($IconsArr[$i] != "." and $IconsArr[$i] != ".."){
					$href=$IconsDir.'/'.$IconsArr[$i];
					echo "<div class='col-md-6 col-sm-6'>";
					echo '<a class="fancybox" href="'.$href.'"><img class="pull-left" src="'.$href.'" />'.$href.'</a>';

					echo "<div class='clearfix'></div><br/><br/><br/></div>";
				}
			}
			echo "</div>";
		}else{
			echo "<div>No icons</div>";
		}
	}
	
	protected function AddUpdatePropertyTypeBuildings($arSectionFields){
		if(CCache::$arIBlocksInfo[$arSectionFields['IBLOCK_ID']]['CODE'] == 'aspro_stroy_catalog'){
			$codeTypeBuildingsProp = 'TYPE_BUILDINGS';
			$arTypeBuildingsValues = array();
			
			$dbRes = CIBlockPropertyEnum::GetList(array('SORT' => 'ASC'), array('CODE' => $codeTypeBuildingsProp));
			while($arRes = $dbRes->Fetch()){
				$arTypeBuildingsValues[$arRes['XML_ID']] = $arRes;
			}
			
			if($arTypeBuildingsValues[$arSectionFields['ID']]){
				$ibpenum = new CIBlockPropertyEnum;
				$ibpenum->Update($arTypeBuildingsValues[$arSectionFields['ID']]['ID'], array('VALUE' => $arSectionFields['NAME']));
			}
			else{
				$dbRes = CIBlockProperty::GetList(array(), array('CODE' => $codeTypeBuildingsProp));
				if($arRes = $dbRes->Fetch()){
					$IDTypeBuildingsProp = $arRes['ID'];
				}
				
				if($IDTypeBuildingsProp){
					$ibpenum = new CIBlockPropertyEnum;
					$ibpenum->Add(array('IBLOCK_ID' => $arSectionFields['IBLOCK_ID'], 'PROPERTY_ID' => $IDTypeBuildingsProp, 'VALUE' => $arSectionFields['NAME'], 'XML_ID' => $arSectionFields['ID']));
				}
			}
		}
	}
	
	function OnAfterIBlockSectionAddHandler($arFields){
		self::AddUpdatePropertyTypeBuildings($arFields);
	}
	
	function OnAfterIBlockSectionUpdateHandler($arFields){
		self::AddUpdatePropertyTypeBuildings($arFields);
	}
	
	function OnAfterIBlockSectionDeleteHandler($arFields){
		if(CCache::$arIBlocksInfo[$arFields['IBLOCK_ID']]['CODE'] == 'aspro_stroy_catalog'){
			$codeTypeBuildingsProp = 'TYPE_BUILDINGS';
			$arTypeBuildingsValues = array();
			
			$dbRes = CIBlockPropertyEnum::GetList(array('SORT' => 'ASC'), array('CODE' => $codeTypeBuildingsProp, 'XML_ID' => $arFields['ID']));
			if($arRes = $dbRes->Fetch()){
				if($arRes['ID']){
					CIBlockPropertyEnum::Delete($arRes['ID']);
				}
			}
		}
	}

	// DO NOT USE - FOR OLD VERSIONS
	public function showPanel(){
	}

	// DO NOT USE - FOR OLD VERSIONS
	function SetSeoMetaTitle(){
		global $APPLICATION, $arSite;
		if(!CSite::inDir(SITE_DIR.'index.php')){
			$PageH1 = $APPLICATION->GetTitle();
			if(!strlen($PageMetaTitleBrowser = $APPLICATION->GetPageProperty('title'))){
				if(!strlen($DirMetaTitleBrowser = $APPLICATION->GetDirProperty('title'))){
					$APPLICATION->SetPageProperty('title', $PageH1.((strlen($PageH1) && strlen($arSite['SITE_NAME'])) ? ' - ' : '' ).$arSite['SITE_NAME']);
				}
			}
		}
		else{
			if(!strlen($PageMetaTitleBrowser = $APPLICATION->GetPageProperty('title'))){
				if(!strlen($DirMetaTitleBrowser = $APPLICATION->GetDirProperty('title'))){
					$PageH1 = $APPLICATION->GetTitle();
					$APPLICATION->SetPageProperty('title', $arSite['SITE_NAME'].((strlen($arSite['SITE_NAME']) && strlen($PageH1)) ? ' - ' : '' ).$PageH1);
				}
			}
		}
	}

	// DO NOT USE - FOR OLD VERSIONS
	function linkShareImage($previewPictureID = false, $detailPictureID = false){
		global $APPLICATION;

		if($linkSaherImageID = ($detailPictureID ? $detailPictureID : ($previewPictureID ? $previewPictureID : false))){
			$APPLICATION->AddHeadString('<link rel="image_src" href="'.CFile::GetPath($linkSaherImageID).'"  />', true);
		}
	}
}
?>