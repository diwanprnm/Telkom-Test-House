<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSPBRevisionEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $spb;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($spb)
    {
        $this->spb = $spb;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('emails.spbRevision', ['data' => $this->spb], function ($m) {
            $m->to($this->spb['customerEmail'])->subject($this->spb['subject']);
        });
    }
}
