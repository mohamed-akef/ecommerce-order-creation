<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $response->assertSee('order_id');
    }



    public function testFailCreation(): void
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
