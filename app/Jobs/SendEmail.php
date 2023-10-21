<?php

namespace App\Jobs;

use App\Mail\NewOffer;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * SendEmail Job
 *
 * This job is sending email notifications about new offers to owners.
 */
class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public User $authUser;
    public Shop $shop;

    /**
     * Create a new job instance.
     */
    public function __construct(User $authUser, Shop $shop)
    {
        $this->authUser = $authUser;
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->authUser->email)->queue(new NewOffer($this->shop));
    }
}
