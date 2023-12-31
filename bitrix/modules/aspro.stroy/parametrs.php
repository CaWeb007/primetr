<?php
/**
 * Stroy module
 * @copyright 2016 Aspro
 */

IncludeModuleLangFile(__FILE__);
$moduleClass = 'CStroy';

// initialize module parametrs list and default values
$moduleClass::$arParametrsList = array(
	'MAIN' => array(
		'TITLE' => GetMessage('MAIN_OPTIONS'),
		'OPTIONS' => array(
			'THEME_SWITCHER' =>	array(
				'TITLE' => GetMessage('THEME_SWITCHER'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'BASE_COLOR' => array(
				'TITLE' => GetMessage('BASE_COLOR'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'CUSTOM' => array('COLOR' => '', 'TITLE' => GetMessage('BASE_COLOR_CUSTOM')),
					'1' => array('COLOR' => '#5baa00', 'TITLE' => GetMessage('BASE_COLOR_1')),
					'2' => array('COLOR' => '#0d897f', 'TITLE' => GetMessage('BASE_COLOR_2')),
					'3' => array('COLOR' => '#1b9e77', 'TITLE' => GetMessage('BASE_COLOR_3')),
					'4' => array('COLOR' => '#188b30', 'TITLE' => GetMessage('BASE_COLOR_4')),
					'5' => array('COLOR' => '#5f58ac', 'TITLE' => GetMessage('BASE_COLOR_5')),
					'6' => array('COLOR' => '#00569c', 'TITLE' => GetMessage('BASE_COLOR_6')),
					'7' => array('COLOR' => '#107bb1', 'TITLE' => GetMessage('BASE_COLOR_7')),
					'8' => array('COLOR' => '#0088cc', 'TITLE' => GetMessage('BASE_COLOR_8')),
					'9' => array('COLOR' => '#497c9d', 'TITLE' => GetMessage('BASE_COLOR_9')),
					'10' => array('COLOR' => '#0fa8ae', 'TITLE' => GetMessage('BASE_COLOR_10')),
					'11' => array('COLOR' => '#e65100', 'TITLE' => GetMessage('BASE_COLOR_11')),
					'12' => array('COLOR' => '#c21f13', 'TITLE' => GetMessage('BASE_COLOR_12')),
					'13' => array('COLOR' => '#b41818', 'TITLE' => GetMessage('BASE_COLOR_13')),
					'14' => array('COLOR' => '#bd1c3c', 'TITLE' => GetMessage('BASE_COLOR_14')),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
			),
			'BASE_COLOR_CUSTOM' => array(
				'TITLE' => GetMessage('BASE_COLOR_CUSTOM'),
				'TYPE' => 'text',
				'DEFAULT' => '5baa00',
				'THEME' => 'Y',
			),
			'COLORED_LOGO' => array(
				'TITLE' => GetMessage('COLORED_LOGO'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'TOP_MENU_FIXED' => array(
				'TITLE' => GetMessage('TOP_MENU_FIXED'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
			),
			'TOP_MENU' => array(
				'TITLE' => GetMessage('TOP_MENU'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'LIGHT' => GetMessage('TOP_MENU_LIGHT'),
					'DARK' => GetMessage('TOP_MENU_DARK'),
					'COLOR' => GetMessage('TOP_MENU_COLOR'),
				),
				'DEFAULT' => 'COLOR',
				'THEME' => 'Y',
			),
			'SIDE_MENU' => array(
				'TITLE' => GetMessage('SIDE_MENU'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'LEFT' => GetMessage('SIDE_MENU_LEFT'),
					'RIGHT' => GetMessage('SIDE_MENU_RIGHT'),
				),
				'DEFAULT' => 'LEFT',
				'THEME' => 'Y',
			),
			'SCROLLTOTOP_TYPE' => array(
				'TITLE' => GetMessage('SCROLLTOTOP_TYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'NONE' => GetMessage('SCROLLTOTOP_TYPE_NONE'),
					'ROUND_COLOR' => GetMessage('SCROLLTOTOP_TYPE_ROUND_COLOR'),
					'ROUND_GREY' => GetMessage('SCROLLTOTOP_TYPE_ROUND_GREY'),
					'ROUND_WHITE' => GetMessage('SCROLLTOTOP_TYPE_ROUND_WHITE'),
					'RECT_COLOR' => GetMessage('SCROLLTOTOP_TYPE_RECT_COLOR'),
					'RECT_GREY' => GetMessage('SCROLLTOTOP_TYPE_RECT_GREY'),
					'RECT_WHITE' => GetMessage('SCROLLTOTOP_TYPE_RECT_WHITE'),
				),
				'DEFAULT' => 'ROUND_COLOR',
				'THEME' => 'N',
			),
			'SCROLLTOTOP_POSITION' => array(
				'TITLE' => GetMessage('SCROLLTOTOP_POSITION'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'TOUCH' => GetMessage('SCROLLTOTOP_POSITION_TOUCH'),
					'PADDING' => GetMessage('SCROLLTOTOP_POSITION_PADDING'),
					'CONTENT' => GetMessage('SCROLLTOTOP_POSITION_CONTENT'),
				),
				'DEFAULT' => 'PADDING',
				'THEME' => 'N',
			),
		),
	),
	'FORMS' => array(
		'TITLE' => GetMessage('FORMS_OPTIONS'),
		'OPTIONS' => array(
			'USE_CAPTCHA_FORM' => array(
				'TITLE' => GetMessage('USE_CAPTCHA_FORM'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'NONE' => GetMessage('USE_CAPTCHA_FORM_NONE'),
					'HIDDEN' => GetMessage('USE_CAPTCHA_FORM_HIDDEN'),
					'IMAGE' => GetMessage('USE_CAPTCHA_FORM_IMAGE'),
					'RECAPTCHA' => GetMessage('USE_CAPTCHA_FORM_RECAPTCHA'),
				),
				'DEFAULT' => 'N',
				'THEME' => 'N',
			),
			'RECAPTCHA_SITE_KEY' => array(
				'TITLE' => GetMessage('RECAPTCHA_SITE_KEY'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'RECAPTCHA_SECRET_KEY' => array(
				'TITLE' => GetMessage('RECAPTCHA_SECRET_KEY'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'RECAPTCHA_NOTE' => array(
				'NOTE' => GetMessage('RECAPTCHA_NOTE'),
				'TYPE' => 'note',
				'THEME' => 'N',
			),
			'PHONE_MASK' => array(
				'TITLE' => GetMessage('PHONE_MASK'),
				'TYPE' => 'text',
				'DEFAULT' => '+7 (999) 999-99-99',
				'THEME' => 'N',
			),
			'DISPLAY_PROCESSING_NOTE' => array(
				'TITLE' => GetMessage('DISPLAY_PROCESSING_NOTE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'N',
			),
			'PROCESSING_NOTE_CHECKED' => array(
				'TITLE' => GetMessage('PROCESSING_NOTE_CHECKED'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'N',
			),			
			'FILE_PROCESSING_NOTE' => array(
				'TITLE' => GetMessage('FILE_PROCESSING_NOTE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/processing_note.php',
				'THEME' => 'N',
			),
			'VALIDATE_PHONE_MASK' => array(
				'TITLE' => GetMessage('VALIDATE_PHONE_MASK'),
				'TYPE' => 'text',
				'DEFAULT' => '^[+][0-9] [(][0-9]{3}[)] [0-9]{3}[-][0-9]{2}[-][0-9]{2}$',
				'THEME' => 'N',
			),
			'DATE_FORMAT' => array(
				'TITLE' => GetMessage('DATE_FORMAT'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'DOT' => GetMessage('DATE_FORMAT_DOT'),
					'HYPHEN' => GetMessage('DATE_FORMAT_HYPHEN'),
					'SPACE' => GetMessage('DATE_FORMAT_SPACE'),
					'SLASH' => GetMessage('DATE_FORMAT_SLASH'),
					'COLON' => GetMessage('DATE_FORMAT_COLON'),
				),
				'DEFAULT' => 'DOT',
				'THEME' => 'N',
			),
			'VALIDATE_FILE_EXT' => array(
				'TITLE' => GetMessage('VALIDATE_FILE_EXT'),
				'TYPE' => 'text',
				'DEFAULT' => 'png|jpg|jpeg|gif|doc|docx|xls|xlsx|txt|pdf|odt|rtf',
				'THEME' => 'N',
			),
		),
	),
	'SOCIAL' => array(
		'TITLE' => GetMessage('SOCIAL_OPTIONS'),
		'OPTIONS' => array(
			'SOCIAL_VK' => array(
				'TITLE' => GetMessage('SOCIAL_VK'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_FACEBOOK' => array(
				'TITLE' => GetMessage('SOCIAL_FACEBOOK'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_TWITTER' =>	array(
				'TITLE' => GetMessage('SOCIAL_TWITTER'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_INSTAGRAM' => array(
				'TITLE' => GetMessage('SOCIAL_INSTAGRAM'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_YOUTUBE' => array(
				'TITLE' => GetMessage('SOCIAL_YOUTUBE'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_ODNOKLASSNIKI' => array(
				'TITLE' => GetMessage('SOCIAL_ODNOKLASSNIKI'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_GOOGLEPLUS' => array(
				'TITLE' => GetMessage('SOCIAL_GOOGLEPLUS'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
		),
	),
	'INDEX_PAGE' => array(
		'TITLE' => GetMessage('INDEX_PAGE_OPTIONS'),
		'OPTIONS' => array(
			'BANNER_WIDTH' => array(
				'TITLE' => GetMessage('BANNER_WIDTH'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'AUTO' => GetMessage('BANNER_WIDTH_AUTO'),
					'WIDE' => GetMessage('BANNER_WIDTH_WIDE'),
					'MIDDLE' => GetMessage('BANNER_WIDTH_MIDDLE'),
					'NARROW' => GetMessage('BANNER_WIDTH_NARROW'),
				),
				'DEFAULT' => 'SECOND',
				'THEME' => 'Y',
			),
			'BIGBANNER_ANIMATIONTYPE' => array(
				'TITLE' => GetMessage('BIGBANNER_ANIMATIONTYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'SLIDE_HORIZONTAL' => GetMessage('ANIMATION_SLIDE_HORIZONTAL'),
					'SLIDE_VERTICAL' => GetMessage('ANIMATION_SLIDE_VERTICAL'),
					'FADE' => GetMessage('ANIMATION_FADE'),
				),
				'DEFAULT' => 'SLIDE_HORIZONTAL',
				'THEME' => 'N',
			),
			'BIGBANNER_SLIDESSHOWSPEED' => array(
				'TITLE' => GetMessage('BIGBANNER_SLIDESSHOWSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '3500',
				'THEME' => 'N',
			),
			'BIGBANNER_ANIMATIONSPEED' => array(
				'TITLE' => GetMessage('BIGBANNER_ANIMATIONSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '600',
				'THEME' => 'N',
			),
			/*'PARTNERSBANNER_SLIDESSHOWSPEED' => array(
				'TITLE' => GetMessage('PARTNERSBANNER_SLIDESSHOWSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '5000',
				'THEME' => 'N',
			),
			'PARTNERSBANNER_ANIMATIONSPEED' => array(
				'TITLE' => GetMessage('PARTNERSBANNER_ANIMATIONSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '600',
				'THEME' => 'N',
			),*/
			/*'TEASERS_INDEX' => array(
				'TITLE' => GetMessage('TEASERS_INDEX'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'PICTURES' => GetMessage('TEASERS_INDEX_PICTURES'),
					'ICONS' => GetMessage('TEASERS_INDEX_ICONS'),
					'NONE' => GetMessage('TEASERS_INDEX_NONE'),
				),
				'DEFAULT' => 'PICTURES',
				'THEME' => 'Y',
			),*/
			'CATALOG_FILTER_INDEX' => array(
				'TITLE' => GetMessage('CATALOG_FILTER_INDEX'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
			),
			'CATALOG_INDEX' => array(
				'TITLE' => GetMessage('CATALOG_INDEX'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
			),
			'CATALOG_FAVORITES_INDEX' => array(
				'TITLE' => GetMessage('CATALOG_FAVORITES_INDEX'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
			),
			'PORTFOLIO_INDEX' => array(
				'TITLE' => GetMessage('PORTFOLIO_INDEX'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
			),
		),
	),
	'CATALOG_PAGE' => array(
		'TITLE' => GetMessage('CATALOG_PAGE_OPTIONS'),
		'OPTIONS' => array(
			'FILTER_VIEW' => array(
				'TITLE' => GetMessage('M_FILTER_VIEW'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'VERTICAL' => GetMessage('M_FILTER_VIEW_VERTICAL'),
					'HORIZONTAL' => GetMessage('M_FILTER_VIEW_HORIZONTAL'),
					'NONE' => GetMessage('M_FILTER_VIEW_NONE'),
				),
				'DEFAULT' => 'VERTICAL',
				'THEME' => 'Y',
			),
		),
	),
	'COUNTERS_GOALS' => array(
		'TITLE' => GetMessage('COUNTERS_GOALS_OPTIONS'),
		'OPTIONS' => array(
			'FILE_COUNTERS' => array(
				'TITLE' => GetMessage('FILE_COUNTERS_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/invis-counter.php',
				'THEME' => 'N',
			),
			'USE_YA_COUNTER' => array(
				'TITLE' => GetMessage('USE_YA_COUNTER_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
			),
			'YA_COUNTER_ID' => array(
				'TITLE' => GetMessage('YA_COUNTER_ID_TITLE'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'USE_FORMS_GOALS' => array(
				'TITLE' => GetMessage('USE_FORMS_GOALS_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'NONE' => GetMessage('USE_FORMS_GOALS_NONE'),
					'COMMON' => GetMessage('USE_FORMS_GOALS_COMMON'),
					'SINGLE' => GetMessage('USE_FORMS_GOALS_SINGLE'),
				),
				'DEFAULT' => 'COMMON',
				'THEME' => 'N',
			),
			'USE_DEBUG_GOALS' => array(
				'TITLE' => GetMessage('USE_DEBUG_GOALS_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'N',
			),
			'GOALS_NOTE' => array(
				'NOTE' => GetMessage('GOALS_NOTE_TITLE'),
				'TYPE' => 'note',
				'THEME' => 'N',
			),
		),
	),
);
?>