<?
namespace Caweb\Main;
use Caweb\Main\Iblock\UpdatePrice;

class Agent {
    public function updatePrice(){
        try {
            $updater = new UpdatePrice();
            $updater->updateElementsPrice();
        }catch (\Exception $exception){
            Tools::sendB24Log('Perimetr Caweb\Main\Iblock\UpdatePrice: '.$exception->getMessage());
        }
        return '\Caweb\Main\Agent::updatePrice();';
    }
}