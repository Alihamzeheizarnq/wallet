<?php

namespace App\Http\Controllers\Api;

use App\Enum\Payment\PaymentStatus;
use App\Events\PaymentApprovedEvent;
use App\Events\PaymentRejectedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PaymentController extends Controller
{
    /**
     * index
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $payments = Payment::latest()->paginate(20);

        return apiResponse()
            ->data(new PaymentCollection($payments))
            ->message(__('payment.messages.payment_list_found_successfully'))
            ->send();
    }

    /**
     * store
     *
     * @param PaymentRequest $request
     * @return JsonResponse
     */
    public function store(PaymentRequest $request): JsonResponse
    {
        $hasPayment = $request->user()
            ->payments()
            ->whereDate('created_at', '>', now()->subMinutes(5))
            ->exists();

        if ($hasPayment) {
            throw new BadRequestException('There is s a error');
        }

        $payment = Payment::create([
            'user_id' => auth()->user()->id,
            'amount' => $request->amount,
            'currency_key' => $request->currency_key,
        ]);

        return apiResponse()
            ->data($payment)
            ->message(__('payment.messages.payment_successfully_created'))
            ->send();
    }

    /**
     * show
     *
     * @param Payment $payment
     * @return JsonResponse
     */
    public function show(Payment $payment): JsonResponse
    {
        return apiResponse()
            ->data(new PaymentResource($payment))
            ->message(__('payment.messages.payment_successfully_found'))
            ->send();
    }

    /**
     * reject
     *
     * @param Payment $payment
     * @return JsonResponse
     */
    public function reject(Payment $payment): JsonResponse
    {
        if ($payment->status !== PaymentStatus::PENDING) {
            throw new BadRequestException('Payment status should be pending');
        }

        $payment->update([
            'status' => PaymentStatus::REJECTED->value,
            'status_updated_by' => auth()->user()->id,
            'status_updated_at' => now(),
        ]);

        PaymentRejectedEvent::dispatch($payment);

        return apiResponse()
            ->data($payment)
            ->send();
    }

    /**
     * approved
     *
     * @param Payment $payment
     * @return JsonResponse
     */
    public function approve(Payment $payment): JsonResponse
    {
        DB::beginTransaction();
        $payment->update([
            'status' => PaymentStatus::APPROVED,
            'status_updated_by' => auth()->user()->id,
            'status_updated_at' => now(),
        ]);

        $balance = auth()->user()
                ->transactions()
                ->where('currency_key', $payment->currency_key)
                ->sum('amount') + $payment->amount;

        $payment->transaction()->create([
            'user_id' => auth()->user()->id,
            'amount' => $payment->amount,
            'currency_key' => $payment->currency_key,
            'balance' => $balance,
        ]);

        DB::commit();

        PaymentApprovedEvent::dispatch($payment);

        return apiResponse()
            ->data($payment)
            ->send();
    }
}
