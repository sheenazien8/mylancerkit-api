<?php

namespace App\Console\Commands;

use App\Mail\ReminderMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Reminder extends Command

{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'daily:reminder';
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
      $users = User::where('status', true)->with(['projects' => function ($query)
      {
          $query->orderBy('deadline', 'asc')
                    ->where('deadline', '>' , Carbon::now()->format('Y-m-d'))
                    ->whereIn('payment_status_id', [1,2,3,4]);
                }])->latest()->get();
      foreach ($users as $user) {
        foreach ($user->projects as $project) {
            if ($project->deadline == Carbon::now()->subDays(-2)->format('Y-m-d') ||
                $project->deadline == Carbon::now()->subDays(-1)->format('Y-m-d') ||
                $project->deadline == Carbon::now()->format('Y-m-d')) {
                $this->info('Reminder messages sent successfully to ' . $user->email. '!!!');
                Mail::to($user->email)->send(new ReminderMail($user));
            }
        }
      }
   }
}
