<?php

namespace App\Jobs;

use App\Http\Controllers\DataController;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveStoresDataJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $dirName, $fileName = null;

    /**
     * Create a new job instance.
     *
     * @param $dirName
     * @param $fileName
     * @return void
     */
    public function __construct($dirName, $fileName)
    {
        $this->dirName = $dirName;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dataController = new DataController();
        $dataController->readData($this->dirName, $this->fileName);
    }
}
