<?php

namespace App\Http\Controllers\Api\V1;

use App\Enum\Payment\PaymentStatus;
use App\Events\PaymentApproved;
use App\Events\PaymentDestroyed;
use App\Events\PaymentRejected;
use App\Events\PaymentCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
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
        $payments = Payment::latest()->paginate(config('app.pre_page'));

        return apiResponse()
            ->data(new PaymentCollection($payments))
            ->message(__('payment.messages.payment_list_found_successfully'))
            ->send();
    }

    /**
     * store
     *
     * @param StorePaymentRequest $request
     * @return JsonResponse
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        $hasPayment = $request->user()
            ->payments()
            ->whereDate('created_at', '>', now()->subMinutes(5))
            ->exists();

        if ($hasPayment) {
            throw new BadRequestException(
                __('payment.errors.time_limit_on_creating_payment', ['minutes' => 5])
            );
        }

        $payment = Payment::create([
            'user_id' => auth()->user()->id,
            'amount' => $request->amount,
            'currency_key' => $request->currency_key,
        ]);

        PaymentCreated::dispatch($payment);

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
            throw new BadRequestException(
                __('payment.errors.you_can_only_decline_pending_payments')
            );
        }

        $payment->update([
            'status' => PaymentStatus::REJECTED->value,
            'status_updated_by' => auth()->user()->id,
            'status_updated_at' => now(),
        ]);

        PaymentRejected::dispatch($payment);

        return apiResponse()
            ->data($payment)
            ->message(__('payment.messages.the_payment_was_successfully_rejected'))
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
            'user_id' => $payment->user_id,
            'amount' => $payment->amount,
            'currency_key' => $payment->currency_key,
            'balance' => $balance,
        ]);

        DB::commit();

        PaymentApproved::dispatch($payment);

        return apiResponse()
            ->data($payment)
            ->message(__('payment.messages.the_payment_was_successfully_approved'))
            ->send();
    }

    /**
     * destroy
     *
     * @param Payment $payment
     * @return JsonResponse
     */
    public function destroy(Payment $payment): JsonResponse
    {
        if ($payment->status !== PaymentStatus::PENDING) {
            throw new BadRequestException(
                __('payment.errors.you_can_only_destroy_pending_payments')
            );
        }

        $payment->delete();

        PaymentDestroyed::dispatch($payment);

        return apiResponse()
            ->message(__('payment.messages.the_payment_was_successfully_destroyed'))
            ->send();
    }
}
