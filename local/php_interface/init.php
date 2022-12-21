<?
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
function Pr($x){
    echo '<pre>';var_dump($x);echo '</pre><hr>';
}
Loader::includeModule('caweb.main');
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'sendBitrix24'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementUpdate', array('Caweb\Main\Events\Iblock', 'calculateBackground'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'calculateBackground'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementAdd', array('Caweb\Main\Events\Iblock', 'cancelElementAction'));
EventManager::getInstance()->addEventHandlerCompatible('iblock', 'OnBeforeIBlockElementDelete', array('Caweb\Main\Events\Iblock', 'cancelElementAction'));
CModule::AddAutoloadClasses(
    '', // не указываем имя модуля
    array(
       'HLBData' => '/local/classes/HLBData.php', // функции работы с Highload блоками
    )
);
define("RATING_HL_BLOCK_ID", 2);
CModule::IncludeModule("forum");
AddEventHandler("forum", "onAfterMessageAdd", "onAfterMessageAddHandler");
//добавляем 1 поле для рейтинга
function onAfterMessageAddHandler($id, $arFields){
    addRatingMessage($id,$_REQUEST['UF_RATING'],$_REQUEST['UF_ELEMENT_ID']);
   // file_put_contents($_SERVER["DOCUMENT_ROOT"]."/add_message.log", date("d-m-Y")."; ID=".print_r($id,1)."; ".print_r($arFields,1).";\n", FILE_APPEND);
}
AddEventHandler("forum", "onAfterMessageDelete", "onAfterMessageDeleteHandler");
//удаляем highload при удалении сообщения
function onAfterMessageDeleteHandler($id, $arFields){
  DeleteRatingMessage($id);
   // file_put_contents($_SERVER["DOCUMENT_ROOT"]."/add_message.log", date("d-m-Y")."; ID=".print_r($id,1)."; ".print_r($arFields,1).";\n", FILE_APPEND);
}
//получаем рейтинг сообщения по id из Hiload
function getRatingByIdMessage($MESSAGE_ID){
    $RATING=5;
    $arFilter = array(
        'UF_MESSAGE_ID'=>$MESSAGE_ID
    ); //задаете фильтр по вашим полям
    //получаем данные от текущего пользователя
    $entity_data_class = HLBData::GetEntityDataClass(RATING_HL_BLOCK_ID);
    $rsData = $entity_data_class::getList(array(
        'select' => array('*'),
        "filter" => $arFilter,
    ));
    $ret = false;    //детальные данные о пользователе  выводятся в шблоне
    if($elIntesity = $rsData->fetch()){
        $ret=$elIntesity;
        $RATING=$ret['UF_RATING'];
    }
    return $RATING;
}
function getIdByIdMessage($MESSAGE_ID){
    $ID=0;
    $arFilter = array(
        'UF_MESSAGE_ID'=>$MESSAGE_ID
    ); //задаете фильтр по вашим полям
    //получаем данные от текущего пользователя
    $entity_data_class = HLBData::GetEntityDataClass(RATING_HL_BLOCK_ID);
    $rsData = $entity_data_class::getList(array(
        'select' => array('*'),
        "filter" => $arFilter,
    ));
    $ret = false;    //детальные данные о пользователе  выводятся в шблоне
    if($elIntesity = $rsData->fetch()){
        $ret=$elIntesity;
        $ID=$ret['ID'];
    }
    return $ID;
}
//добавляем рейтинг
function addRatingMessage($MESSAGE_ID ,$RATING,$ELEMENT_ID){
    $RATING=(int)$RATING;
    $arData=array(
        'UF_MESSAGE_ID'=>$MESSAGE_ID,
        'UF_RATING'=>$RATING,
        'UF_ELEMENT_ID'=>$ELEMENT_ID,
    );
    $result_add = HLBData::addHLBData (RATING_HL_BLOCK_ID, $arData);
}
function DeleteRatingMessage($MESSAGE_ID){
  $ID=0;
  $arFilter = array(
      'UF_MESSAGE_ID'=>$MESSAGE_ID
  ); //задаете фильтр по вашим полям
  //получаем данные от текущего пользователя
  $entity_data_class = HLBData::GetEntityDataClass(RATING_HL_BLOCK_ID);
  $rsData = $entity_data_class::getList(array(
      'select' => array('*'),
      "filter" => $arFilter,
  ));
  $ret = false;    //детальные данные о пользователе  выводятся в шблоне
  if($elIntesity = $rsData->fetch()){
      $ret=$elIntesity;
      $ID=$ret['ID'];
  }
    HLBData::deleteHLBData (2, $ID);
}
?>