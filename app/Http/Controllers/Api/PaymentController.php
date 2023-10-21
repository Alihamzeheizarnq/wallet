<?php

namespace App\Http\Controllers\Api;

use App\Enum\Payment\Status;
use App\Events\PaymentApprovedEvent;
use App\Events\PaymentRejectedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentResource;
use App\Mail\notifyRejectedPayment;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\ValidatedData;
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
            'user_id' => auth()->user()->id,
            'amount' => $request->amount,
            'currency_id' => $request->currency_id
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

        PaymentRejectedEvent::dispatch($payment);

        return $this->successResponse($payment);
    }

    /**
     * approved
     *
     * @param Payment $payment
     * @return JsonResponse
     */
    public function approved(Payment $payment): JsonResponse
    {
        if ($payment->status !== Status::PENDING->value) {
            throw new BadRequestException('Payment status should be pending');
        }

        if ($payment->transaction) {
            throw new BadRequestException('There is a transaction for this payment');
        }

        $payment->update([
            'status' => Status::APPROVED->value
        ]);

        $payment->transaction()->create([
            'user_id' => auth()->user()->id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'balance' => Transaction::where('user_id', auth()->user()->id)->sum('amount'),
        ]);

        PaymentApprovedEvent::dispatch($payment);

        return $this->successResponse($payment);
    }
}
