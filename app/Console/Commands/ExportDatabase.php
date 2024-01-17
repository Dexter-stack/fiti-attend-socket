<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $host = config('database.connections.mysql.host');
        $user = config('database.connections.mysql.username');
        $password ="";
        $dbname = config('database.connections.mysql.database');
        //set the file name and path
        $filname = 'database-'.date('Y-m-d').rand(1,10000).'.sql';
        $path = storage_path('app/'.$filname);

        //export Database
        $cmd = "mysqldump --opt -h $host -u $user -p $password $dbname > $path";
        system($cmd);
        // return Command::SUCCESS;
        $this->info("Database exported to $path");
    }
}
