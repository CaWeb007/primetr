<?
namespace Caweb\Main\Events;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;
use Caweb\Main\ORD;
use Caweb\Main\Tools;


Loc::loadLanguageFile(__FILE__);
class Main{
    /**@var $oAdminList \CAdminList*/

    public static function addContextButtonForOrd(&$oAdminList){
        $type = Iblock::ADV_IBLOCK_TYPE;
        $iblock = Iblock::MAIN_BANNERS_IBLOCK_ID;
        $tableId = md5($type.".".$iblock);
        $propertyId = Iblock::PROPERTY_MARKER_ORD_ID;
        $checkIblock = $oAdminList->table_id == "tbl_iblock_list_".$tableId;
        if (!$checkIblock){
            $type = Iblock::CONTENT_IBLOCK_TYPE;
            $iblock = Iblock::NEWS_SALES_IBLOCK_ID;
            $tableId = md5($type.".".$iblock);
            $checkIblock = $oAdminList->table_id == "tbl_iblock_list_".$tableId;
        }
        if (!$checkIblock) return;
        if(isset($_REQUEST["del_filter"]) && $_REQUEST["del_filter"] != "")
            $find_section_section = -1;
        elseif(isset($_REQUEST["find_section_section"]))
            $find_section_section = $_REQUEST["find_section_section"];
        else
            $find_section_section = -1;
        $sThisSectionUrl = '&type='.urlencode($type).'&lang='.LANGUAGE_ID.'&IBLOCK_ID='.$iblock.'&find_section_section='.intval($find_section_section);
        $rows = $oAdminList->aRows;
        $propertyId = Tools::getInstance()->getPropertyIdByCode(Iblock::PROPERTY_MARKER_ORD_CODE, $iblock);
        /**@var $row \CAdminListRow*/
        foreach ($rows as $row){
            $fields = $row->aFields;
            $ID = $row->id;
            if (substr($ID,0,1) !== 'E') continue;
            $prop = $row->aFields['PROPERTY_'.$propertyId];
            if (empty($fields['PROPERTY_'.$propertyId])) continue;
            if ($row->arRes['ACTIVE'] !== 'Y') continue;
            $markerExist = !empty($prop['view']['value']);
            $arActions = $row->aActions;
            $arActions[] = array(
                'SEPARATOR' => true
            );
            if (!$markerExist){
                $arActions[] = array(
                    'TEXT'=> 'Создать креатив ОРД',
                    'ACTION' => $oAdminList->ActionDoGroup($row->id, 'createOrdCreative', $sThisSectionUrl),
                    'ONCLICK' => ''
                );
            }else{
                $arActions[] = array(
                    'TEXT'=> 'Обновить креатив ОРД',
                    'ACTION' => $oAdminList->ActionDoGroup($row->id, 'updateOrdCreative', $sThisSectionUrl),
                    'ONCLICK' => ''
                );
            }
            $row->AddActions($arActions);
        }
    }

    public static function adminOrdActionHandler(){
        $propertyCode = Iblock::PROPERTY_MARKER_ORD_CODE;
        $oRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        $create = $oRequest['action_button'] === 'createOrdCreative';
        $update = $oRequest['action_button'] === 'updateOrdCreative';
        $ID = $oRequest->get('ID');
        $type = substr($ID,0,1);
        $ID = intval(substr($ID, 1));
        if (!$create && !$update) return;

        $iblockId = Iblock::MAIN_BANNERS_IBLOCK_ID;
        $checkIblock = (int)$oRequest->get('IBLOCK_ID') == $iblockId;
        if (!$checkIblock){
            $iblockId = Iblock::NEWS_SALES_IBLOCK_ID;
            $checkIblock = (int)$oRequest->get('IBLOCK_ID') == $iblockId;
        }
        if (!$checkIblock) return;

        if (!$oRequest->isAdminSection()) return;
        if ($type !== 'E') return;
        Loader::includeModule('iblock');
        $element = \CIBlockElement::GetByID($ID)->GetNextElement();
        if (empty($element)) return;
        $fields = $element->GetFields();
        $prop = $element->GetProperty($propertyCode)['VALUE'];
        if ($create && !empty($prop)) return;
        if ($update && empty($prop)) return;
        if ($create){
            $externalID = md5($fields['ID']);
        }
        if ($update){
            $externalID = $fields['XML_ID'];
        }
        try {
            Loader::includeModule('caweb.main');
            $ord = new ORD(true);
            $ord->setBody(array(
                "name"=> $fields['NAME'],
                "brand"=> $fields['NAME'],
                "category"=> $fields['NAME'],
                "description"=> $fields['NAME'],
                "okveds" => array('46.73')
            ));
            $ord->setExternalId($externalID);
            $ord->doQuery();
            $marker = $ord->getMarker();
            if ($create){
                $elementEntity = new \CIBlockElement();
                $elementEntity::SetPropertyValuesEx($ID, $iblockId, array(
                    $propertyCode => $marker
                ));
                $elementEntity->Update($ID, array('XML_ID' => $externalID));
            }
        }catch (\Exception $exception){
            echo '<script>alert("'.$exception->getMessage().'")</script>';
        }
    }
}
