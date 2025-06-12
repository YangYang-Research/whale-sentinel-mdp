<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Traits\SyncProfileToServiceTrait;

class SyncProfileToServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SyncProfileToServiceTrait;

    protected $type, $id, $name;
    /**
     * Create a new job instance.
     */
    public function __construct(string $type, string $id, string $name)
    {
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->makeRequestSyncProfileToService($this->type, $this->id, $this->name);
    }
}
