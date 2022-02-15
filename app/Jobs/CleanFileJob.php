<?php

namespace App\Jobs;

use App\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log, Storage;

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
            $files = File::where('status','=', 0)->orderBy('created_at', 'DESC')->take(1)->get();
            foreach($files as $file) {
                Log::info('file deleted successfully'. $file->url);
                $url = $file->url;
                $domain = env('AWS_URL', 'https://norep68cf33f0074c418bab3b13445eed70bf162639-dev.s3.us-east-2.amazonaws.com/');
                $getpath = explode($domain, $url);
                if(count($getpath)==2){
                    Log::info('path to delete '.$getpath[1]);
                    $response = Storage::disk('s3')->delete(trim($getpath[1]));
                    $file = $file->delete();
                }
            }
        } catch (\Exception $e) {
                Log::error('Oops something went wrong.', ['error'=> $e->getMessage(), 
                'line_no'=> $e->getLine()]);
        }
    }
}
