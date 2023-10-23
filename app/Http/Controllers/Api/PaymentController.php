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
use Illuminate\Support\Facades\DB;
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
        //TODO check the payment
        $payment = Payment::create([
            'user_id' => auth()->user()->id,
            'amount' => $request->amount,
            'currency_key' => $request->currency_key
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

        //TODO check only being pendding
        if ($payment->status === Status::APPROVED->value) {
            //TODO read from trans
            throw new BadRequestException('this payment has already approved before');
        }

        if ($payment->status === Status::REJECTED->value) {
            //TODO read from trans
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

        //TODO read the text from lang file
        if ($payment->transaction()->exists()) {
            throw new BadRequestException('There is a transaction for this payment');
        }

        DB::beginTransaction();
        //TODO rename Status enum to Payment/PaymentStatusEnum
        $payment->update([
            'status' => Status::APPROVED->value
        ]);

        $amount = Transaction::where('user_id', auth()->user()->id)
                ->sum('amount') + $payment->amount;

        $payment->transaction()->create([
            'user_id' => auth()->user()->id,
            'amount' => $payment->amount,
            'currency_key' => $payment->currency_key,
            'balance' => $amount,
        ]);

        DB::commit();

        PaymentApprovedEvent::dispatch($payment);

        return $this->successResponse($payment);
    }
}
