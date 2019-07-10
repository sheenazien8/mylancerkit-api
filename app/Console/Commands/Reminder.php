<?php

namespace App\Console\Commands;

use App\Mail\ReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Reminder extends Command

{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'monthly:reminder';
   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Send email to all the users at the end of the month';
   /**
    * Create a new command instance.
    *
    * @return void
    */
   public function __construct()
   {
       parent::__construct();
   }
   /**
    * Execute the console command.
    *
    * @return mixed
    */
   public function handle()
   {
      Mail::to('sheenazien08@gmail.com')->send(new ReminderMail('user'));
   }
}
