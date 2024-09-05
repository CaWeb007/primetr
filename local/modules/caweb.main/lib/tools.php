<?
namespace Caweb\Main;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Web\Uri;

class Tools {
    private static $instance = null;
    private $host = null;
    private $uriInstance = null;
    private const B24Url = 'https://crm.strlog.ru/rest/52/n64271cljpvl8cnj/im.message.add.json';
    private const B24DIALOG_ID = 52;
    public static function getInstance(){
        if (self::$instance === null){
            self::$instance = new self;
        }
        return self::$instance;
    }
    static public function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
        $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        $str_end = "";
        if ($lower_str_end) {
            $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        }
        else {
            $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        $str = $first_letter . $str_end;
        return $str;
    }
    public function getHost(){
        if ($this->host) return $this->host;
        $this->host = Application::getInstance()->getContext()->getRequest()->getHttpHost();
        return $this->host;
    }
    public function getUriInstance(){
        if ($this->uriInstance) return $this->uriInstance;
        $uri = new Uri(Application::getInstance()->getContext()->getRequest()->getRequestUri());
        if (empty($uri->getHost()))
            $uri->setHost($this->getHost());
        $this->uriInstance = $uri;
        return $this->uriInstance;
    }
    public function getMarkerOrdUri(string $markerORD, string $link = null){
        if (empty($link)) return false;
        $uri = new Uri($link);
        if (empty($uri->getHost()))
            $uri->setHost($this->getHost());
        return $uri->addParams(array('erid' => $markerORD))->getUri();
    }
    public function getPropertyIdByCode(string $propertyCode, $iblockID = false){
        return (int)\CIBlockProperty::GetByID($propertyCode, $iblockID, false)->GetNext()['ID'];
    }
    public static function getUtm(){
        $request = Context::getCurrent()->getRequest();
        return unserialize($request->getCookie('UTM'));
    }
    public static function sendB24Log(string $message){
        $queryUrl = self::B24Url;
        $queryData = http_build_query(
            array(
                "MESSAGE" => $message,
                "SYSTEM" => "N",
                "DIALOG_ID" => self::B24DIALOG_ID,
            ));
        $curl = curl_init();
        curl_setopt_array($curl,
            array( CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_POST => 1, CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $queryUrl, CURLOPT_POSTFIELDS => $queryData));
        $result = curl_exec($curl);
        curl_close($curl);
    }
    public static function sendB24Response($method, $fields){
        $queryUrl = "https://crm.strlog.ru/rest/52/ewopeeg6jjzaudzo/".$method;
        $queryData = http_build_query($fields);
        $curl = curl_init();
        curl_setopt_array($curl,
            array( CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_POST => 1, CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $queryUrl, CURLOPT_POSTFIELDS => $queryData));
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, 1);
        return $result;
    }
}