<?php

namespace App\Http\Middleware;

use App\Exceptions\Errors;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TaskOwnershipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $taskId = $request->route('id');

        if ($taskId !== null) {
            $task = Task::findOrFail($taskId);

            if ($task->user_id !== Auth::id()) {
                Errors::InvalidOperation();
            }
        }

        return $next($request);
    }
}
