<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvRequest;
use App\Jobs\ProcessPaymentFileJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CsvUploadController extends Controller
{
    public function store(CsvRequest $request): Response
    {

        $file = $request->file('file');

        // If this exist
        if (Storage::exists('my_csvs/payments.csv')) {
            throw new ConflictHttpException('Resource already exists.');
        }

        $extension = $file->getClientOriginalExtension();

        $path = $file->storeAs(
            'my_csvs',
            'payments.'.$extension,
            's3'
        );

        ProcessPaymentFileJob::dispatch($path);

        return response()->noContent();
    }

    public function show(Request $request): JsonResponse
    {

        // Can be used to start proccessing if needed
        if (! Storage::exists('my_csvs/payments.csv')) {
            throw new NotFoundHttpException;
        }

        ProcessPaymentFileJob::dispatch('my_csvs/payments.csv');

        return response()->json([
            'message' => 'Processing begun',
        ], 200);
    }
}
