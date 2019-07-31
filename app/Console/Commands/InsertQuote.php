<?php

namespace App\Console\Commands;

use App\Mail\ReminderMail;
use App\Models\Quote;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class InsertQuote extends Command

{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'command:insert_quote';
   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Insert Quote per Day';
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
        $client = new Client();
        $response = $client->request('GET', 'https://quotes.rest/qod.json');
        $json = json_decode($response->getBody())->contents;
        // dd($json->quotes[0]);
        $data = [
            'quote' => $json->quotes[0]->quote,
            'author' => $json->quotes[0]->author
        ];
        $quote = new Quote();
        $quote->fill($data);
        $quote->save();

        return 'Success';
   }
}
