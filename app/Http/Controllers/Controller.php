<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Mobile;
use App\Shop;
use App\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    public $authenticated;
    public $userId;
    public $shopId;
    public $currentShop;
    //protected $brands;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        if(Auth::check()) {
            $this->userId = Auth::user()->id;
            $this->authenticated = true;

            //only if user has setup a shop
            if(Auth::user()->shops()->first()){
                if(Session::get('shop_id')){
                    $this->shopId = Session::get('shop_id');
                    $this->currentShop = Shop::find($this->shopId);
                }
                else{
                    $this->shopId = Auth::user()->shops()->first()->id;
                    $this->currentShop = Shop::find($this->shopId);
                }
            }
        }
        else
            $this->authenticated = false;

        /*$this->brands = collect([
            'Samsung' => 'Samsung',
            'OPPO' => 'OPPO',
            'Huawei' => 'Huawei',
            'Apple' => 'Apple',
            'HTC' => 'HTC',
            'Nokia' => 'Nokia',
            'Sony' => 'Sony',
            'Motorola' => 'Motorola',
            'LG' => 'LG',
            'QMobile' => 'LG',
            'Haier' => 'Haier',
            'BlackBerry' => 'BlackBerry',
            'Voice' => 'Voice',
            'Sony Ericsson' => 'Sony Ericsson',
            'Lenovo' => 'Lenovo',
            'Microsoft' => 'Microsoft',
            'Rivo' => 'Rivo',
            'Acer' => 'Acer',
            'HP' => 'HP',
            'i-Mate' => 'i-Mate',
            'Asus' => 'Asus',
            'China Mobiles' => 'China Mobiles',
            'Trend' => 'Trend',
            'VGO Tel' => 'VGO Tel',
            'Club' => 'Club',
            'DANY' => 'DANY',
            'Peace' => 'Peace',
            'XPOD' => 'XPOD',
            'GRight' => 'GRight',
            'Calme' => 'Calme',
            'Panasonic' => 'Panasonic',
            'Avia' => 'Avia',
            'MegaGate' => 'MegaGate',
            'G Five' => 'G Five',
            'Dell' => 'Dell',
            'Mmobile' => 'Mmobile',
            'ZTE' => 'ZTE',
            'Ufone' => 'Ufone',
            'Zong' => 'Zong',
            'Telenor' => 'Telenor',
            'Pepsi' => 'Pepsi',
            'Mobilink' => 'Mobilink',
            'Maxx' => 'Maxx',
            'Xiaomi' => 'Xiaomi',
            'Alcatel' => 'Alcatel',
            'Google' => 'Google',
            'Infinix' => 'Infinix',
            'iNew' => 'iNew',
            'Amazon' => 'Amazon'
        ]);*/
        /*$this->brands = collect([
            'Apple' => 'Apple',
            'Dell' => 'Huawei',
            'HP' => 'OPPO',
            'Samsung' => 'Samsung',
            'Lenovo' => 'HTC',
            'Acer' => 'Nokia',
            'Sony' => 'Sony',
            'Asus' => 'Motorola',
            'Toshiba' => 'Toshiba',
            'Razer' => 'Razer',
            'LG' => 'LG',
            'Microsoft' => 'Microsoft',
            'chromebook' => 'chromebook',
            'Panasonic' => 'Panasonic',
            'MSI' => 'MSI',
        ]);
        foreach($this->brands as $brand){
            $b = new Brand();
            $b->name = $brand;
            $b->type = 'laptop';
            $b->save();
        }*/
        /*$mobileStorage = [8, 16, 32, 64, 128, 256];
        foreach ($mobileStorage as $s){
            $m = new Storage();
            $m->storage = $s;
            $m->save();
        }*/
    }
}
