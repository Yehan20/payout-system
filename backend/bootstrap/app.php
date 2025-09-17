<?php

use App\Jobs\DailyPayOutJob;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        //  $schedule->call('proccess:payments')->dailyAt('23:59');
        $schedule->job(new DailyPayOutJob)->dailyAt('23:59');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Unauthorized exception
        $exceptions->render(function (AuthenticationException $e, Request $request) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 401);
        });

        // Too many requests
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {

            if ($request->is('api/*')) {

                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 429);
            }
        });

        // Too many requests
        $exceptions->render(function (ConflictHttpException $e, Request $request) {

            if ($request->is('api/*')) {

                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 409);
            }
        });

        // Validated exception
        $exceptions->render(function (ValidationException $e, Request $request) {

            if ($request->is('api/*')) {

                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // fallback exception
        $exceptions->render(function (Exception $e, Request $request) {

            if ($request->is('api/*')) {

                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        });
    })->create();
