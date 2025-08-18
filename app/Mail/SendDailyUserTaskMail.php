<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendDailyUserTaskMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public $tasks,
        public array $statusCounts,
        public Carbon $date,
    ) {
        $this->onQueue('mail');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your daily task summary',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_user_tasks',
            with: [
                'user' => $this->user,
                'tasks' => $this->tasks,
                'statusCounts' => $this->statusCounts,
                'date' => $this->date,
            ],
        );
    }

  
    public function attachments(): array
    {
        return [];
    }
}
