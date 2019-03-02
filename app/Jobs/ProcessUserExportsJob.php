<?php

namespace App\Jobs;

use App\Services\ProcessUserExportsJobService;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessUserExportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * @var User $user
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->queue = env('EXPORT_USERS_QUEUE');
    }

    /**
     * Method to executing the job.
     *
     * @param ProcessUserExportsJobService $processUserExportsJobService
     */
    public function handle(ProcessUserExportsJobService $processUserExportsJobService)
    {
        $processUserExportsJobService->processUserExports($this->user);
    }
}
