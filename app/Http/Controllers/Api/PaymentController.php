<?php

namespace App\Http\Controllers\Api;

use App\Enum\Payment\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PaymentController extends Controller
{
    use ApiResponse;
    public function index(): JsonResponse
    {
        $payments = Payment::latest()->paginate(20);

        return $this->successResponse($payments);
    }
    public function store(PaymentRequest $request): JsonResponse
    {
        $payment = Payment::create([
            'user_id' => 1,
            'amount' => $request->amount,
            'currency' => $request->currency
        ]);

        return $this->successResponse($payment);
    }

    public function show(Payment $payment): JsonResponse
    {
        return $this->successResponse($payment);
    }

    public function reject(Payment $payment): JsonResponse
    {
        if ($payment->status === Status::APPROVED->value) {
            throw new BadRequestException('this payment has already approved before');
        }

        if ($payment->status === Status::REJECTED->value) {
            throw new BadRequestException('this payment has already rejected before');
        }

        $payment->update([
            'status' => Status::REJECTED->value
        ]);

        return $this->successResponse($payment);
    }
}
