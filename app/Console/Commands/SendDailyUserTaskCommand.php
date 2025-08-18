<?php

namespace App\Console\Commands;

use App\Jobs\SendDailyUserTaskJob;
use App\Models\User;
use Illuminate\Console\Command;

class SendDailyUserTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-user-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch queued jobs to email daily task summaries to each user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dispatchedJobs = 0;

        User::query()
            ->select(['id'])
            ->orderBy('id')
            ->chunkById(500, function ($users) use (&$dispatchedJobs) {
                foreach ($users as $user) {
                    SendDailyUserTaskJob::dispatch($user->id);
                    $dispatchedJobs++;
                }
            });

        $this->info("Dispatched {$dispatchedJobs} daily task email jobs.");
    }
}
