<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Payment;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user->refresh());

        $this->actingAs($user)->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function test_get_all_payments()
    {
        $response = $this->json('GET', '/api/payments');

        $response->assertStatus(200);
    }

    public function test_get_single_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('GET', "/api/payments/{$payment->unique_id}");

        $response->assertStatus(200);
    }

    public function test_create_payment()
    {
        $paymentData = [
            'user_id' => 1, // Replace with a valid user ID
            'amount' => 100.00,
            'status' => 'pending',
            'currency' => 'USD',
            'unique_id' => 'unique_payment_id',
        ];

        $response = $this->json('POST', '/api/payments', $paymentData);

        $response->assertStatus(201);
    }

    public function test_reject_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('POST', "/api/payments/{$payment->unique_id}/reject");

        $response->assertStatus(200);
    }

    public function test_approve_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->json('POST', "/api/payments/{$payment->unique_id}/approve");

        $response->assertStatus(200);
    }
}
