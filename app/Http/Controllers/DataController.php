<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Color;
use App\Jobs\SaveStoresDataJob;
use App\Mobile;
use App\ProductData;
use App\Shop;
use App\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;

class DataController extends Controller
{

    protected $f = null;
    public $colors = null;
    public $years = null;
    protected $globalBrandName = null;
    public $shopName = null;
    protected $dir = null;
    protected $dirName = null;
    protected $months = null;

    /**
     * DataController constructor.
     */
    public function __construct()
    {
        $this->f = fopen('data.txt', 'a+');
        $this->colors = Color::pluck('color')->toArray();
        $this->years = range(2000, date('Y'));
        $this->shopName = 'Telemart';
        $this->dir = public_path() . '/online';
        $this->months = [
            'January' => '01',
            'February' => '02',
            'March' => '03',
            'April' => '04',
            'May' => '05',
            'June' => '06',
            'July' => '07',
            'August' => '08',
            'September' => '09',
            'October' => '10',
            'November' => '11',
            'December' => '12',
            'Septeber' => '09'
        ];
    }

    public function readAndStoreGsmData(){
        $di = new \RecursiveDirectoryIterator(public_path().'/scrap/');

        foreach (new \RecursiveIteratorIterator($di) as $filename => $file) {
            if($file->getExtension() == 'txt' && $file->isFile()){
                try
                {
                    $onlyFileName = explode(".", $file->getFilename())[0];
                    $gsmData = file_get_contents($filename);
                    $this->saveData($gsmData, $onlyFileName);
                }
                catch (FileNotFoundException $exception)
                {
                    echo ("The file doesn't exist");
                }
            }
        }
    }

    /**
     * read data from files
     */
    public function readData($dir, $fileName){
        $filename = public_path() . '/online/'. $dir .'/' . $fileName;
        $onlineData = file_get_contents($filename);
        $this->compareData($onlineData);
    }



    /**
     * compare titles to categorize
     * @param $onlineData
     */
    public function compareData($onlineData){

        $onlineLines = explode("\n", $onlineData); // this is your array of words
        //sort the online data files
        //based on brand name
        usort($onlineLines, function($a, $b)
        {
            return strcmp(explode(";", $a)[0], explode(";", $b)[0]);
        });

        $colors = $this->colors;
        $skipArray = array();
        //dd($onlineLines);
        $dbMobiles = null;
        foreach($onlineLines as $onlineLine){
            if($onlineLine == "" || $onlineLine == "\n"){
                continue;
            }
            $onlineTitle = explode(";", $onlineLine)[1];

            //get the brand name from every line
            $brandName = strtolower(explode(";", $onlineLine)[0]);

            //if brand name not already in array
            //then fetch the data
            //since brand has changed
            if(!(DB::table('temp')->where('brand', $brandName)->where('shop', $this->shopName)->first())){
                $dbMobiles = Brand::where('name', $brandName)->first();
                if($dbMobiles){
                    $dbMobiles = Brand::find($dbMobiles->id)->mobiles()->where('mobiles.title', '<>', '')->get();
                }
                array_push($skipArray, $brandName);
                DB::table('temp')->insert(['brand' => $brandName, 'shop' => $this->shopName]);
            }
            //if brand name is in the array already
            //then keep on reading the data
            //loop through database mobiles
            //to compare
            $mobileMatched = null;
            if(count($dbMobiles) > 0){
                if ($mobileMatched == true)
                    return;
                foreach ($dbMobiles as $mobile){

                    //if a mobile has matched then
                    //skip that line since it wont
                    //match with any other mobile
                    if($mobileMatched == true){
                        break;
                    }
                    $gsmTitle = $mobile->title;

                    //echo $brandName . ' = ' . $gsmFileName . '<br>';
                    //replace everything including (content) with "" galaxy (2016) = galaxy
                    $gsmTitle = preg_replace("/\\((.*?)\\)|(:\\))*/", "" , $gsmTitle);

                    //replace the / char and content around it with ""
                    $gsmTitle = preg_replace("/[a-zA-Z0-9]+\\/[a-zA-Z0-9]+/", "" , $gsmTitle);

                    $yearPass = false;
                    $colorPass = false;
                    $overallPass = false;
                    $exactMatch = false;

                    //dd($colors);
                    $years = $this->years;

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

                    if(!($exactMatch == true || $overallPass == true)){
                        //now handle the case where space comes after title
                        foreach ($years as $y){

                            //there must be a space after title
                            //after space a year, gb, dual sim, 2g or 3g etc
                            if(preg_match("/^.*?$gsmTitle(\\s)+(?:(($y)|(3g|4g|lte|2g)|(Dual Sim)|(\\p{N}GB))?)/i", $onlineTitle)){
                                $yearPass = true;
                            }
                        }
                    }

                    if(!($exactMatch == true || $overallPass == true || $yearPass == true)) {
                        foreach ($colors as $c){
                            $c = preg_replace('/\\W/', '', $c);
                            if(preg_match("/^.*?$gsmTitle(\\s)+(?:(($c)|(3g|4g|lte|2g)|(Dual Sim)|(\\p{N}GB))?)/i", $onlineTitle)){
                                $colorPass = true;
                            }
                        }
                    }

                    if($yearPass || $overallPass || $exactMatch){
                        echo 'color pass '. $this->returnTrueFalse($colorPass) .'<br>'. ' year pass ' . $this->returnTrueFalse($yearPass) . '<br>'. ' overall pass ' .
                            $this->returnTrueFalse($overallPass).'<br>' . 'exact match ' . $this->returnTrueFalse($exactMatch). '<br>';
                        echo $gsmTitle . ' = ' . $onlineTitle . '<br>';
                        $this->saveComparedData($gsmTitle, $onlineLine, $this->shopName, $mobile->id);
                        $mobileMatched = true;
                    }
                    else{
                        /*echo 'All of the passes failed';
                        echo $gsmTitle . ' = ' . $onlineTitle . '<br>';*/
                    }
                }
            }//end if db mobiles is null
            else{
                //echo $brandName. '<br>';
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

            //check if line is empty
            if($line == '' || $line == '\n' || $line == null){
                continue;
            }

            $data = explode("@#$%", $line);
            $releaseDate = null;
            if(isset($data[5])) {
                if (preg_match('/^Released|Discontinued/im', $data[5])) {
                    $releaseDate = '2000-01-01';
                } else {
                    $releaseMonth = !isset(explode(",", $data[5])[1]) ? 01 : $this->monthNumber(trim(explode(",", $data[5])[1]));
                    $releaseYear = !isset(explode(",", $data[5])[0]) ? 2000 : preg_replace('/[a-zA-Z.\\s]/i', "", explode(",", $data[5])[0]) == null ? 2000 : preg_replace('/[a-zA-Z.\\s]/i', "", explode(",", $data[5])[0]);
                    $releaseDate = $releaseYear . '-' . $releaseMonth . '-01';
                    echo $data[0] . ' = ' . $data[5] .' : after :  ' . $releaseDate . '<br>';
                }
            }
            else{
                $releaseDate = '2000-01-01';
            }

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

            $mobile = Mobile::updateOrCreate(
                [
                    'title' => $data[0],
                    'brand_id' => $brand->id,
                    'model' => $this->getModel($gsmTitle)
                ],
                [
                    'image' => url('/').'/scrap/'. $brandName . '/' .$data[0].'.png',
                    'release_date' => $releaseDate,
                ]
            );

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



    /**
     * @param $gsmTitle
     * @param $onlineData
     * @param $shopName
     * @param $mobileId
     */
    public function saveComparedData($gsmTitle, $onlineData, $shopName, $mobileId){

        $mobileController = new MobileController();
        $onlineData = explode(";", $onlineData);
        $unmodifiedTitle = $gsmTitle;
        $oldPrice = $onlineData[3];
        $newPrice = $onlineData[4];
        $productLink = $onlineData[5];
        //replace everything including (content) with "" galaxy (2016) = galaxy
        $gsmTitle = preg_replace("/\\((.*?)\\)|(:\\))*/", "" , $gsmTitle);

        //replace the / char and content around it with ""
        $gsmTitle = preg_replace("/[a-zA-Z0-9]+\\/[a-zA-Z0-9]+/", "" , $gsmTitle);
        $shopId = Shop::where('shop_name', ucwords($shopName))->first()->id;

        $mobileData = ProductData::where('mobile_id', $mobileId)->where('shop_id', $shopId)->first();
        if(!$mobileData){
            ProductData::create([
                'shop_id' => $shopId,
                'mobile_id' => $mobileId,
                'link' => $productLink,
                'old_price' => (string)$oldPrice,
                'current_price' => (string)$newPrice,
                'discount' => $mobileController->discount($newPrice, $oldPrice),
                'local_online' => 'o',
                'stock' => '111999'
            ]);
        }
        else{
            ProductData::where('mobile_id', $mobileId)->where('shop_id', $shopId)->update([
                'shop_id' => $shopId,
                'mobile_id' => $mobileId,
                'link' => $productLink,
                'old_price' => (string)$oldPrice,
                'current_price' => (string)$newPrice,
                'discount' => $mobileController->discount($newPrice, $oldPrice),
                'local_online' => 'o',
                'stock' => '111999'
            ]);

            /*$product = ProductData::where('mobile_id', $mobileId)->where('shop_id', $shopId)->first();
            $product->colors()->sync($colorIds);
            $product->storages()->sync($storageIds);*/
        }
    }



    /**
     * returns model, slug for the title
     */
    public function getModel($gsmTitle){
        return preg_replace("/\\s/", "-", strtolower($gsmTitle));
    }

    function returnTrueFalse($var){
        return $var == true ? "true" : "false";
    }



    /**
     * read the data from
     * all the online shops and
     * dispatch job for each file
     *
     */
    function listFolderFiles()
    {
        foreach (new \DirectoryIterator($this->dir) as $fileInfo) {
            if (!$fileInfo->isDot()) {
                //echo $fileInfo->getFilename() . ' file type = ' . $fileInfo->getType();
                if($fileInfo->getType() == 'dir'){
                    $this->dirName = $fileInfo->getFilename();
                    $this->shopName = ucwords($fileInfo->getFilename());
                }
                if($fileInfo->getType() == 'file' && !(in_array($fileInfo->getFilename(), ['laptops', 'laptop']))){
                    $this->readData($this->dirName, $fileInfo->getFilename());
                    //$this->dispatch(new SaveStoresDataJob($this->dirName, $fileInfo->getFilename()));
                }
                if ($fileInfo->isDir()) {
                    $this->dir = $fileInfo->getPathname();
                    $this->listFolderFiles();
                }
            }
        }
    }



    /**
     * get the month number
     * accepts month name
     *
     * @param $monthName
     * @return mixed
     */
    public function monthNumber($monthName){
        foreach ($this->months as $key => $value){
            if(strtolower($monthName) == strtolower($key)){
                return $value;
            }
            else{
                return '01';
            }
        }
    }
}
