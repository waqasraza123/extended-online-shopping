<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Mobile;
use App\MobileData;
use App\Shop;
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

                    if(preg_match("/^[a-zA-Z0-9]*(-|\\s)*$gsmTitle(\\s|-)+(\\p{N}GB)*\\B/i", $onlineTitle)){
                        //save the compared data to db and link shops
                        echo $gsmTitle . ' = ' . $onlineTitle . '<br>';
                        $this->saveComparedData($brandName, $gsmLine, $onlineLine, "Zemlak Inc");
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
            //make sure to save on ly storage at storage place
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

            foreach ($storages as $s){
                foreach ($colors as $c){
                    Mobile::firstOrCreate([
                        'title' => $data[0],
                        'brand_id' => $brand->id,
                        'image' => url('/').'/scrap/'.$brandName . '/' .$data[0].'.png',
                        'storage' => $s == 1024 ? 1 : $s,
                        //replace the special char from color
                        'color' => preg_replace ('/[^\p{L}\p{N}]/u', ' ', $c) == null ? "no color" : preg_replace ('/[^\p{L}\p{N}]/u', '_', $c),
                        'model' => $this->getModel($gsmTitle)
                    ]);
                }
            }
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
        $oldPrice = rand(50000, 100000);
        $newPrice = 1000000;
        while ($newPrice > $oldPrice || $newPrice == 0)
            $newPrice = rand(50000, 100000);
        MobileData::firstOrCreate([
            'shop_id' => $shopId,
            'mobile_id' => $mobileId,
            'link' => $productLink,
            'old_price' => (string)$oldPrice,
            'current_price' => (string)$newPrice,
            'discount' => $mobileController->discount($newPrice, $oldPrice),
            'local_online' => 'l'
        ]);
    }

    /**
     * returns model, slug for the title
     */
    public function getModel($gsmTitle){
        echo preg_replace("/\\s/", "-", strtolower($gsmTitle));
        return preg_replace("/\\s/", "-", strtolower($gsmTitle));
    }
}
