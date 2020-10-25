<?php

namespace App\Jobs;

use App\User;
use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReminderEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    /**
     * Create a new job instance.
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $testTo = array(
            'mail' => 'danielchristianto77@gmail.com',
            'name' => 'Daniel Christianto Widodo'
        );

        $mailer->send('emails.reminder', ['user' => $this->user], function ($m) use ($testTo) {
            $m->to($testTo['mail'])->subject('Your Reminder!');
        });
    }
}
