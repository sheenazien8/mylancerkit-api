<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;


    public $user;
    public $projects;
    public $pastDue;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $projects, $pastDue = null)
    {
        $this->user = $user;
        $this->projects = $projects;
        $this->pastDue = $pastDue;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd($this->projects);
        return $this->from(env('MAIL_USERNAME'))
                    ->view('emails.reminder');
    }
}
