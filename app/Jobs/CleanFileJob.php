<?php

namespace App\Jobs;

use App\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log, Storage;
use Carbon\Carbon;

class CleanFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $files = File::where([
                    ['created_at', '<=', Carbon::now()->subDay()],
                    ['status', '=', 0],
                ])->orderBy('created_at', 'DESC')->get();
            foreach($files as $file) {
                Log::info('file deleted successfully'. $file->url);
                $url = $file->url;
                $domain = env('AWS_URL', 'https://norep68cf33f0074c418bab3b13445eed70bf162639-dev.s3.us-east-2.amazonaws.com/');
                $getpath = explode($domain, $url);
                if(count($getpath)==2){
                    Log::info('path to delete '.$getpath[1]);
                    Storage::disk('s3')->delete(trim($getpath[1]));
                    $file = $file->delete();
                }
            }
        } catch (\Exception $e) {
                Log::error('Oops something went wrong.', ['error'=> $e->getMessage(), 
                'line_no'=> $e->getLine()]);
        }
    }
}
