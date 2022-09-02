<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{

    use DatabaseMigrations;

    public function testSuccessCreation(): void
    {
        $this->seed();

        $response = $this->post(
            '/api/order',
            [
                "products" => [
                    [
                        "product_id" => 1,
                        "quantity" => 2,
                    ]
                ]
            ]
        );

        $response->assertStatus(201);

        $orderId = json_decode($response->getContent(), true)['order_id'];

        $response->assertJson(['order_id' => $orderId]);

        $order = Order::find($orderId);

        $this->assertEquals(
            Order::STATUS_PLACED,
            $order->status
        );

        $orderProducts = OrderProduct::where('order_id', $orderId)->get();
        $this->assertEquals(
            Order::STATUS_PLACED,
            $order->status
        );
    }

    public function testFailCreationByInvalidRequest(): void
    {
        $this->runDatabaseMigrations();
        $this->seed();

        $response = $this->post(
            '/api/order',
            [
                "products" => [
                    [
                        "product_id" => 1,
                    ]
                ]
            ]
        );

        $response->assertStatus(500);
    }

    public function testFailCreationByStockLimit(): void
    {
        $this->runDatabaseMigrations();
        $this->seed();

        $response = $this->post(
            '/api/order',
            [
                "products" => [
                    [
                        "product_id" => 1,
                        "quantity" => 20000,
                    ]
                ]
            ]
        );

        $response->assertStatus(500);
    }
}
