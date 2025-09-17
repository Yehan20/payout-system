<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvRequest;
use App\Jobs\ProcessTransactionJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CsvUploadController extends Controller
{
    public function store(CsvRequest $request): Response
    {

        if (! $request->hasFile('file')) {
            throw new NotFoundHttpException;
        }
        $file = $request->file('file');

        if (Storage::exists('my_csvs/payments.csv')) {
            throw new ConflictHttpException('Resource already exists.');
        }

        $extension = $file->getClientOriginalExtension();

        $path = $file->storeAs(
            'my_csvs',
            'payments.'.$extension,
            's3'
        );

        ProcessTransactionJob::dispatch($path);

        return response()->noContent();
    }

    public function show(Request $request)
    {

        // Can be used to start proccessing
        if (! Storage::exists('my_csvs/payments.csv')) {
            throw new NotFoundHttpException;
        }

        ProcessTransactionJob::dispatch('my_csvs/payments.csv');

        return response()->json([
            'message' => 'Processing began',
        ], 200);
    }
}
