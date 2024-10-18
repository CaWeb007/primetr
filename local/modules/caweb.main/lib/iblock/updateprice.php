<?
namespace Caweb\Main\Iblock;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\IO\File;

class UpdatePrice {
    private const LINK = 'https://cloud.mail.ru/public/Jafq/rbk3FYJfm';
    private const IBLOCK_ID = 20;
    private const PROPERTY_NAME = 'PRICE';
    private const LOGIN = 'pr@strlog.ru';
    private $filePath = '/upload/price.xlsx';

    public function __construct(){
        Loader::includeModule('iblock');
        $this->filePath = Application::getDocumentRoot().$this->filePath;
        $this->getFile();
    }
    public function updateElementsPrice(){
        $updateArray = $this->getData();
        foreach ($updateArray as $item) {
            $this->setPrice($item);
        }

    }
    protected function getCSV(): \CCSVData{
        $csv = new \CCSVData('R', true);
        $csv->SetDelimiter(';');
        $csv->LoadFile($this->filePath);
        if (empty($csv->Fetch()))
            throw new \Exception('Empty CSV');
        return $csv;
    }
    protected function getFile(){
        $url = 'https://cloud.mail.ru/api/v2/dispatcher?api=2&email=' . self::LOGIN . '&_=' . time();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $answer = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $weblink_prefix = $answer['body']['weblink_get'][0]['url'];
        $weblink_suffix = str_replace('https://cloud.mail.ru/public', '', self::LINK);
        $content = file_get_contents($weblink_prefix . $weblink_suffix);
        if (empty($content))
            throw new \Exception('Download fail');
        $file = new File($this->filePath);
        $file->putContents($content, File::REWRITE);
    }
    protected function getData(){
        include_once(Application::getDocumentRoot().'/local/php_interface/include/lib/exel/PHPExcel.php');
        $tmpfname = $this->filePath;
        $excelReader = \PHPExcel_IOFactory::createReaderForFile($tmpfname);
        $excelObj = $excelReader->load($tmpfname);
        $worksheet = $excelObj->getSheet(0);
        $lastRow = $worksheet->getHighestRow();
        $result = array();
        for ($row = 2; $row <= $lastRow; $row++) {
            $result[] = array(
                'ID' => $worksheet->getCell('A'.$row)->getValue(),
                'NAME' => $worksheet->getCell('B'.$row)->getValue(),
                'PRICE' => $worksheet->getCell('C'.$row)->getValue()
            );
        }
        if (empty($result))
            throw new \Exception('Empty update array');
        return $result;
    }
    protected function getUpdateArray(): array{
        $csv = $this->getCSV();
        $updateArray = array();
        while ($ar = $csv->Fetch()){
            $updateArray[] = array(
                'ID' => $ar[0],
                'PRICE' => mb_convert_encoding($ar[2], 'UTF-8', 'windows-1251')
            );
        }
        if (empty($updateArray))
            throw new \Exception('Empty update array');
        return $updateArray;
    }
    protected function setPrice(array $element){
        if (empty($element['ID']) && empty($element['PRICE'])) return;
        \CIBlockElement::SetPropertyValuesEx(
            (int)$element['ID'],
            self::IBLOCK_ID,
            array(
                self::PROPERTY_NAME => $element['PRICE']
            )
        );
    }
}