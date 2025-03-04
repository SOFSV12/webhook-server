<?php

namespace App\Jobs;

use Throwable;
use App\Models\Joke;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookServer\WebhookCall;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendJokeWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected $business;
    protected $joke;
    protected $name;

    public function __construct($business, $joke, $name)
    {
        $this->business = $business;
        $this->joke     = $joke;
        $this->name     = $name;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Prepare webhook payload
            $payload = [
                'business'  => $this->business->name,
                'date'      => now()->toDateString(),
                'joke'      => "Hey {$this->name}, {$this->joke}",
                'timestamp' => now()->toDateTimeString(),
            ];

            // Store in database before sending
            Joke::create([
                'business_id' => $this->business->id,
                'name'        => $this->name,
                'joke'        => $this->joke,
            ]);

            $url = $this->business->hook_url;
            // Attempt webhook call
            WebhookCall::create() 
            ->url($url)
            ->payload($payload)
            ->useSecret('my-first-web-hook')
            ->dispatch();

        } catch (Throwable $e) {
            Log::error("Webhook failed: " . $e->getMessage(), [
                'business' => $this->business->id,
                'name'     => $this->name,
                'joke'     => $this->joke,
            ]);

            $this->fail($e); // Mark job as failed
        }
    }
}
