<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Task Summary</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 16px;
        }

        .muted {
            color: #6B7280;
        }

        .card {
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 16px;
            margin-top: 12px;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 9999px;
            background: #E5E7EB;
            font-size: 12px;
        }

        .title {
            margin: 0;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Hello {{ $user->username ?? $user->email }},</h2>
        <p class="muted">Here is your task activity for {{ $date->toFormattedDateString() }}.</p>

        <div class="card">
            <h3 class="title">Status overview</h3>
            <ul>
                <li>Pending: {{ $statusCounts['pending'] ?? 0 }}</li>
                <li>In progress: {{ $statusCounts['in-progress'] ?? 0 }}</li>
                <li>Completed: {{ $statusCounts['completed'] ?? 0 }}</li>
            </ul>
        </div>

        <div class="card">
            <h3 class="title">Tasks created today ({{ $tasks->count() }})</h3>
            @if($tasks->isEmpty())
            <p class="muted">No new tasks today.</p>
            @else
            <ul>
                @foreach($tasks as $task)
                <li>
                    <strong>{{ $task->title }}</strong>
                    <span class="badge">{{ $task->status }}</span>
                    <div class="muted">{{ $task->created_at->toDayDateTimeString() }}</div>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        <p class="muted">This is an automated email. You can ignore if not relevant.</p>
    </div>
</body>

</html>