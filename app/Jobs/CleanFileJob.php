<?php

namespace App\Jobs;

use App\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

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
            Log::info('file deleted successfully'. json_encode($files));
            foreach($files as $file) {
                Log::info('file deleted successfully'. json_encode($file));
                $response = Storage::disk('s3')->delete(trim($file->url));
                $file = $file->delete();
                Log::info('file deleted successfully'. $file->url);
            }
        } catch (\Exception $e) {
                Log::error('Oops something went wrong.', ['error'=> $e->getMessage(), 
                'line_no'=> $e->getLine()]);
        }
    }
}
