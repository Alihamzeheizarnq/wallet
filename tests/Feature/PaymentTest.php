<?php

namespace Tests\Feature;

use App\Enum\Payment\PaymentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\ApiStructure;
use Tests\TestCase;
use App\Models\Payment;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentTest extends TestCase
{
    use RefreshDatabase;
    use ApiStructure;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user->refresh());

        $this->actingAs($user)->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function test_get_all_payments()
    {
        $response = $this->json('GET', route('api.payment.index'));

        $response->assertStatus(200);
    }

    public function test_check_the_message_comes_from_getting_all_payment()
    {
        $response = $this->json('GET', route('api.payment.index'));

        $response->assertJsonFragment([
            'message' => __('payment.messages.payment_list_found_successfully')
        ]);
    }

    public function test_check_pagination_comes_from_getting_all_payment()
    {
        $response = $this->json('GET', route('api.payment.index'));

        $response->assertJsonStructure($this->responseStructure());
    }

    public function test_get_single_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('GET', route('api.payment.show', $payment));

        $response->assertStatus(200);
    }

    public function test_created_payment_message()
    {
        $paymentData = Payment::factory()->make();

        $response = $this->json('POST', route('api.payment.store'), $paymentData->toArray());

        $response->assertJsonFragment([
            'message' => __('payment.messages.payment_successfully_created')
        ]);
    }

    public function test_there_should_be_no_error_created_payment_message()
    {
        $paymentData = Payment::factory()->make();

        $response = $this->json('POST', route('api.payment.store'), $paymentData->toArray());

        $response->assertJsonFragment([
            'errors' => []
        ]);
    }

    public function test_check_structure_the_created_payment()
    {
        $paymentData = Payment::factory()->make();

        $response = $this->json('POST', route('api.payment.store'), $paymentData->toArray());

        $response->assertJsonStructure([
            'data' => [
                "user_id",
                "amount",
                "currency_key",
                "unique_id",
                "updated_at",
            ]
        ]);
    }

    public function test_created_payment_should_not_return()
    {
        $paymentData = Payment::factory()->make();

        $response = $this->json('POST', route('api.payment.store'), $paymentData->toArray());

        $responseData = $response->decodeResponseJson();

        $this->assertArrayNotHasKey('id', $responseData->json('data'));
    }

    public function test_check_requirement_before_creating_payment()
    {
        $response = $this->json('POST', route('api.payment.store'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'errors' => [
                "amount",
                "currency_key",
            ]
        ]);
    }

    public function test_reject_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('PATCH', route('api.payment.reject', $payment));

        $response->assertStatus(200);
    }

    public function test_rejected_payment_message()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('PATCH', route('api.payment.reject', $payment));

        $response->assertJsonFragment([
            'message' => __('payment.messages.the_payment_was_successfully_rejected')
        ]);
    }

    public function test_should_be_prevented_rejecting_payment_if_its_not_pending()
    {
        $payment = Payment::factory()->create([
            'status' => PaymentStatus::APPROVED
        ]);

        $response = $this->json('PATCH', route('api.payment.reject', $payment));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment([
            'message' => __('payment.errors.you_can_only_decline_pending_payments')
        ]);
    }


    public function test_approve_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('PATCH', route('api.payment.approve', $payment));

        $response->assertStatus(200);
    }

    public function test_approved_payment_message()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('PATCH', route('api.payment.approve', $payment));

        $response->assertStatus(200);
    }

    public function test_there_should_be_transaction_for_approved_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('PATCH', route('api.payment.approve', $payment));

        $response->assertJsonFragment([
            'message' => __('payment.messages.the_payment_was_successfully_approved')
        ]);
    }

    public function test_transaction_amount_should_be_equal_with_payment_amount()
    {
        $payment = Payment::factory()->create();

        $this->json('PATCH', route('api.payment.approve', $payment));

        $this->assertEquals($payment->transaction->amount, $payment->amount);
    }

    public function test_after_approving_the_user_balance_should_be_right()
    {
        $payment = Payment::factory()->create();

        $this->json('PATCH', route('api.payment.approve', $payment));

        $this->assertEquals($payment->transaction->balance, $payment->user->getBalance($payment->currency));
    }

    public function test_should_be_prevented_approved_payment_if_its_not_pending()
    {
        $payment = Payment::factory()->create([
            'status' => PaymentStatus::REJECTED
        ]);

        $response = $this->json('PATCH', route('api.payment.approve', $payment));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment([
            'message' => __('payment.errors.you_can_only_approve_pending_payments')
        ]);
    }

    public function test_destroy_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('DELETE', route('api.payment.destroy', $payment));

        $response->assertStatus(200);
    }

    public function test_destroyed_payment_message()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('DELETE', route('api.payment.destroy', $payment));

        $response->assertJsonFragment([
            'message' => __('payment.messages.the_payment_was_successfully_destroyed')
        ]);
    }

    public function test_there_is_no_payment_after_deleted()
    {
        $payment = Payment::factory()->create();

        $this->json('DELETE', route('api.payment.destroy', $payment));

        $this->assertFalse($payment->exists());
    }

    public function test_should_be_prevented_destroyed_payment_if_its_not_pending()
    {
        $payment = Payment::factory()->create([
            'status' => PaymentStatus::APPROVED
        ]);

        $response = $this->json('DELETE', route('api.payment.destroy', $payment));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment([
            'message' => __('payment.errors.you_can_only_destroy_pending_payments')
        ]);
    }
}
