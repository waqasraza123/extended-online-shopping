<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Color;
use App\Mobile;
use App\ProductData;
use App\Shop;
use App\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;

class DataController extends Controller
{
    protected $f = null;
    public function __construct()
    {
        $this->f = fopen('data.txt', 'a+');
    }

    /**
     * read data from files
     */
    public function readData(){
        $filename = public_path() . '/online/daraz/smartphones.txt';
        $onlineData = file_get_contents($filename);

        $di = new \RecursiveDirectoryIterator(public_path().'/scrap/');

        foreach (new \RecursiveIteratorIterator($di) as $filename => $file) {

            if($file->getExtension() == 'txt' && $file->isFile()){
                /*if(strtolower(explode(".", $file->getFileName())[0]) == $brandName){*/

                    try
                    {
                        $onlyFileName = strtolower(explode(".", $file->getFilename())[0]);
                        $gsmData = file_get_contents($filename);
                        //$this->saveData($gsmData, $onlyFileName);
                        $this->compareData($gsmData, $onlineData, $onlyFileName);

                    }
                    catch (FileNotFoundException $exception)
                    {
                        die("The file doesn't exist");
                    }
                //}
            }
            /*if(@is_array(getimagesize($file))){
                echo 'true';
            } else {
                echo 'false';
            }*/
        }

    }

    /**
     * compare titles to categorize
     * @param $localTitle
     * @param $onlineTitle
     */
    public function compareData($gsmData, $onlineData, $gsmFileName){

        //first check that brand name matches the filename
        //then start the comparison
        $gsmLines = explode("\n", $gsmData); // this is your array of words
        $onlineLines = explode("\n", $onlineData); // this is your array of words

        foreach($onlineLines as $onlineLine){
            if($onlineLine == ""){
                continue;
            }
            foreach($gsmLines as $gsmLine){

                if($gsmLine == null){
                    continue;
                }

                $gsmTitle = explode(";", $gsmLine)[0];
                $onlineTitle = explode(";", $onlineLine)[1];

                $brandName = strtolower(explode(";", $onlineLine)[0]);

                //echo $brandName . ' = ' . $gsmFileName . '<br>';
                //replace everything including (content) with "" galaxy (2016) = galaxy
                $gsmTitle = preg_replace("/\\((.*?)\\)|(:\\))*/", "" , $gsmTitle);

                //replace the / char and content around it with ""
                $gsmTitle = preg_replace("/[a-zA-Z0-9]+\\/[a-zA-Z0-9]+/", "" , $gsmTitle);

                if($brandName == $gsmFileName){
                    $yearPass = false;
                    $colorPass = false;
                    $overallPass = false;
                    $exactMatch = false;

                    $colors = Color::pluck('color')->toArray();
                    //dd($colors);
                    $years = range(2000, date('Y'));

                    if($gsmTitle == 'B' || $gsmTitle == 'b'){
                        if(preg_match("/^.*?(?:(\\s|-|_|:|\\|)?)([^[G]])$gsmTitle(?:(\\s)?)(-|_|:|\\|)+/i", $onlineTitle)){
                            $overallPass = true;
                        }
                    }

                    if($gsmTitle == 'Z' || $gsmTitle == 'z'){
                        if(preg_match("/^.*?(?:(\\s|-|_|:|\\|)?)([^[GH]])$gsmTitle(?:(\\s)?)(-|_|:|\\|)+/i", $onlineTitle)){
                            $overallPass = true;
                        }
                    }

                    //if exact title or followed by - or _ or :
                    if(preg_match("/^.*?(?:(\\s|-|_|:|\\|)?)$gsmTitle$/i", $onlineTitle)){
                        $exactMatch = true;
                    }

                    if(preg_match("/^.*?(?:(\\s|-|_|:|\\|)?)$gsmTitle(?:(\\s)?)(-|_|:|\\|)+/i", $onlineTitle)){
                        $overallPass = true;
                    }

                    //now handle the case where space comes after title
                    foreach ($years as $y){

                        //there must be a space after title
                        //after space a year, gb, dual sim, 2g or 3g etc
                        if(preg_match("/^.*?$gsmTitle(\\s)+(?:(($y)|(3g|4g|lte|2g)|(Dual Sim)|(\\p{N}GB))?)/i", $onlineTitle)){
                            $yearPass = true;
                        }
                    }

                    foreach ($colors as $c){
                        $c = preg_replace('/\\W/', '', $c);
                        echo $c . '<br>';
                        if(preg_match("/^.*?$gsmTitle(\\s)+(?:(($c)|(3g|4g|lte|2g)|(Dual Sim)|(\\p{N}GB))?)/i", $onlineTitle)){
                            $colorPass = true;
                        }
                    }

                    if($colorPass || $yearPass || $overallPass || $exactMatch){
                        echo 'color pass '. $this->returnTrueFalse($colorPass) .'<br>'. ' year pass ' . $this->returnTrueFalse($yearPass) . '<br>'. ' overall pass ' .
                            $this->returnTrueFalse($overallPass).'<br>' . 'exact match ' . $this->returnTrueFalse($exactMatch). '<br>';
                        echo $gsmTitle . ' = ' . $onlineTitle . '<br>';

                        $this->saveComparedData($brandName, $gsmLine, $onlineLine, "Daraz");
                    }
                }
            }
        }
    }

    /**
     * save data in database
     * of gsm, whatmobile
     */
    public function saveData($gsmData, $brandName){
        $content = explode("\n", $gsmData);

        $brand = Brand::firstOrCreate(['name' => ucwords($brandName)]);
        foreach($content as $line){

            $data = explode(";", $line);

            //if more than one storages start a loop
            //make sure to save only storage at storage place
            //not the ram memory as in some cases
            if(isset($data[1])){
                //if gb or mb text exits, it is memory
                if (preg_match("/(GB|MB)+/i", $data[1]) !== 0){
                    $storages = explode("/", trim(preg_replace("/([a-zA-Z]*(GB|MB|RAM|ROM))+|((\\(.*?\\)))/i", "", $data[1])));
                    $storages = preg_replace("/[^\\p{N}]/u", "", $storages);
                }
                else
                    $storages = 0;
            }
            else{
                $storages = 0;
            }
            if(isset($data[2])){
                $colors = explode(",", str_replace("and", ",", trim(str_replace("/", ",", $data[2]))));
            }
            else{
                $colors = "various";
            }

            if (!(is_array($storages) or ($storages instanceof Traversable))){
                $storages = [$storages];
            }
            if (!(is_array($colors) or ($colors instanceof Traversable))){
                $colors = [$colors];
            }

            //replace everything including (content) with "" galaxy (2016) = galaxy
            $gsmTitle = preg_replace("/\\((.*?)\\)|(:\\))*/", "" , $data[0]);

            //replace the / char and content around it with ""
            $gsmTitle = preg_replace("/[a-zA-Z0-9]+\\/[a-zA-Z0-9]+/", "" , $gsmTitle);

            $mobile = Mobile::firstOrCreate([
                'title' => $data[0],
                'brand_id' => $brand->id,
                'image' => url('/').'/scrap/'.$brandName . '/' .$data[0].'.png',
                /*'storage' => $s == 1024 ? 1 : $s,
                //replace the special char from color
                'color' => preg_replace ('/[^\p{L}\p{N}]/u', ' ', $c) == null ? "no color" : preg_replace ('/[^\p{L}\p{N}]/u', '_', $c),
                */'model' => $this->getModel($gsmTitle)
            ]);

            /*insert colors and storages*/
            $colorIds = array();
            $storageIds = array();

            foreach($colors as $c){
                $color = Color::firstOrCreate([
                    'color' => $c
                ]);
                array_push($colorIds, $color->id);
            }

            foreach($storages as $s){
                $storage = Storage::firstOrCreate([
                    'storage' => $s
                ]);
                array_push($storageIds, $storage->id);
            }

            $mobile->colors()->sync($colorIds);
            $mobile->storages()->sync($storageIds);
        }
    }

    public function saveComparedData($brandName, $baseData, $onlineData, $shopName){

        $mobileController = new MobileController();
        $onlineData = explode(";", $onlineData);
        $baseData = explode(";", $baseData);

        $gsmTitle = $baseData[0];
        $oldPrice = $onlineData[3];
        $newPrice = $onlineData[4];
        $productLink = $onlineData[5];
        //replace everything including (content) with "" galaxy (2016) = galaxy
        $gsmTitle = preg_replace("/\\((.*?)\\)|(:\\))*/", "" , $gsmTitle[0]);

        //replace the / char and content around it with ""
        $gsmTitle = preg_replace("/[a-zA-Z0-9]+\\/[a-zA-Z0-9]+/", "" , $gsmTitle);
        $shopId = Shop::where('shop_name', ucwords($shopName))->first()->id;

        $mobileId = Mobile::where('title', $baseData[0])->first()->id;
        /*$oldPrice = rand(50000, 100000);
        $newPrice = 1000000;
        while ($newPrice > $oldPrice || $newPrice == 0)
            $newPrice = rand(50000, 100000);*/
        $mobileData = ProductData::where('mobile_id', $mobileId)->where('shop_id', $shopId)->first();
        if(!$mobileData){
            ProductData::create([
                'shop_id' => $shopId,
                'mobile_id' => $mobileId,
                'link' => $productLink,
                'old_price' => (string)$oldPrice,
                'current_price' => (string)$newPrice,
                'discount' => $mobileController->discount($newPrice, $oldPrice),
                'local_online' => 'o'
            ]);
        }
    }

    /**
     * returns model, slug for the title
     */
    public function getModel($gsmTitle){
        echo preg_replace("/\\s/", "-", strtolower($gsmTitle));
        return preg_replace("/\\s/", "-", strtolower($gsmTitle));
    }

    function returnTrueFalse($var){
        return $var == true ? "true" : "false";
    }
}
