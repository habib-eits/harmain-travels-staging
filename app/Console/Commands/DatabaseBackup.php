<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use File;
use DB;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Storage;

use Mail;
class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create copy of mysql dump for existing database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = "backup-" . env('DB_DATABASE') ."_".Carbon::now()->format('Y-m-d') ."_". Carbon::now()->format('H:i:m') .".gz";
  
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
  
        $returnVar = NULL;
        $output  = NULL;
  
        exec($command, $output, $returnVar);
        

        // send email
       $data["email"] = 'extensivebooks@gmail.com';
        
        $data["subject"] = $filename;
        
       
        

         
        $files = [
            storage_path('app/backup/'. $filename),
            // public_path('pdf/1599882252.png'),
        ];
        
//Storage::url($filename)
        
        Mail::raw('Database backup taken, click to download from app '. URL('../storage/app/backup/'.$filename)  , function ($message) use($data, $files) {
            $message->to($data["email"])
             ->cc('mysql.database.dump@gmail.com')
            // ->bcc('mysql.database.dump@gmail.com')
            ->subject($data["subject"]);


            foreach ($files as $file){
                $message->attach($file);
            }
        });
  
        /*Mail::send('email', $data, function($message)use($data, $files) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["subject"]);
            foreach ($files as $file){
                $message->attach($file);
            }
            
        });*/






    }
}
