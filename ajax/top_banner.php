<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('caweb.main');
$banner = \CIBlockElement::GetList(array(), array('IBLOCK_ID' => \Caweb\Main\Events\Iblock::BANNER_IBLOCK_ID),
    false, false, array('DETAIL_PICTURE','PREVIEW_PICTURE','PROPERTY_BACKGROUND','PROPERTY_LINK'))->Fetch();
if (empty($banner)){
    echo \Bitrix\Main\Web\Json::encode(array('error' => 'no_banner'));
    die();
}
if (empty($banner['PROPERTY_LINK_VALUE']))
    $banner['PROPERTY_LINK_VALUE'] = 'javascript:void();';
$result = array(
    'desktopBanner' => \CFile::GetPath((int)$banner['DETAIL_PICTURE']),
    'mobileBanner' => \CFile::GetPath((int)$banner['PREVIEW_PICTURE']),
    'background' => $banner['PROPERTY_BACKGROUND_VALUE'],
    'link' => $banner['PROPERTY_LINK_VALUE'],
);
echo \Bitrix\Main\Web\Json::encode($result);