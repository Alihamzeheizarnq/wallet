<?php

namespace App\Http\Controllers\Api;

use App\Enum\Payment\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentResource;
use App\Mail\notifyRejectedPayment;
use App\Models\Payment;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PaymentController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $payments = Payment::latest()->paginate(20);

        return $this->successResponse(
            new PaymentCollection($payments),
            __('payment.messages.payment_list_found_successfully')
        );
    }

    public function store(PaymentRequest $request): JsonResponse
    {
        $payment = Payment::create([
            'user_id' => 1,
            'amount' => $request->amount,
            'currency' => $request->currency
        ]);

        return $this->successResponse(
            $payment,
            __('payment.messages.payment_successfully_created')
        );
    }

    public function show(Payment $payment): JsonResponse
    {
        return $this->successResponse(
            new PaymentResource($payment),
            __('payment.messages.payment_successfully_found')
        );
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

        Mail::to(
            User::find(1)
        )->send(
            new notifyRejectedPayment($payment)
        );


        return $this->successResponse($payment);
    }
}
