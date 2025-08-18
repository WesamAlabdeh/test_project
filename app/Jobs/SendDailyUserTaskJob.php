<?php

namespace App\Jobs;

use App\Mail\SendDailyUserTaskMail;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDailyUserTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $userId;


    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }


    public function handle(): void
    {
        $user = User::query()->find($this->userId);

        if (!$user || empty($user->email)) {
            return;
        }

        $today = Carbon::today();

        $tasksToday = Task::query()
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->orderByDesc('created_at')
            ->get();

        $statusCounts = Task::query()
            ->where('user_id', $user->id)
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        Mail::to($user->email)->send(new SendDailyUserTaskMail(
            user: $user,
            tasks: $tasksToday,
            statusCounts: $statusCounts,
            date: $today,
        ));
    }
}
