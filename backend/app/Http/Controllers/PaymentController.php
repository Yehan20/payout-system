<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentController extends Controller
{
    //

    public function index(Request $request): JsonResource
    {

        return PaymentResource::collection(Payment::orderBy('id', 'desc')->paginate(20));
    }
}
