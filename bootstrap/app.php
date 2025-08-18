<?php

use App\Exceptions\ApiException;
use App\Http\Middleware\AbilityMiddleware;
use App\Http\Middleware\TaskOwnershipMiddleware;
use App\Utils\Logger;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'ability' => AbilityMiddleware::class,
            'task.owner' => TaskOwnershipMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Exception $exception, Request $request) {
            Logger::LogException($exception);
            $response = match (true) {
                $exception instanceof AuthenticationException => response()->json([
                    'status' => 'failed',
                    'error' => 'NOT_AUTHENTICATED',
                    'message' => 'You are not authenticated',
                ])->setStatusCode(401),
                $exception instanceof AuthenticationException => response()->json([
                    'status' => 'failed',
                    'error' => 'NOT_AUTHENTICATED',
                    'message' => 'You are not authenticated',
                ])->setStatusCode(401),
                $exception instanceof ApiException => $exception->response(),
                $exception instanceof ValidationException =>  response()->json([
                    'status' => 'fail',
                    'error' => $exception->validator->errors()->first()
                ])->setStatusCode(422),
                $exception instanceof NotFoundHttpException => response()->json([
                    'status' => 'failed',
                    'error' => 'RESOURCE_NOT_FOUND',
                    'message' => 'Resource Not Found',
                ])->setStatusCode(404),
                $exception instanceof RouteNotFoundException => response()->json([
                    'status' => 'failed',
                    'error' => 'NOT_AUTHENTICATED',
                    'message' => 'You are not authenticated',
                ])->setStatusCode(404),
                default => response()->json([
                    'status' => 'fail',
                    'error' => 'INTERNAL_SERVER_ERROR',
                    'message' => 'Something went wrong',
                ])->setStatusCode(500)
            };

            return $response;
        });
    })->create();
