<?php

namespace Tests\Modules\Invoices\Presentation\Controllers;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Infrastructure\Models\EloquentCompany;
use App\Modules\Invoices\Infrastructure\Models\EloquentInvoice;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_returns_invoice_when_exists()
    {
        $invoice = $this->createInvoiceInDatabase();

        $response = $this->getJson("/api/invoices/{$invoice->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $invoice->id]);
    }

    public function test_show_returns_404_when_not_found()
    {
        $response = $this->getJson('/api/invoices/' . Uuid::uuid4());

        $response->assertStatus(404)
            ->assertJsonFragment(['error' => 'Invoice not found']);
    }

    private function createInvoiceInDatabase(): EloquentInvoice
    {
        $faker = Factory::create();
        $company = EloquentCompany::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => $faker->company(),
            'street' => $faker->streetAddress(),
            'city' => $faker->city(),
            'zip' => $faker->postcode(),
            'phone' => $faker->phoneNumber(),
            'email' => $faker->safeEmail(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return EloquentInvoice::create([
            'id' => Uuid::uuid4()->toString(),
            'number' => $faker->uuid(),
            'date' => $faker->date(),
            'due_date' => $faker->date(),
            'company_id' => $company->id,
            'status' => StatusEnum::cases()[array_rand(StatusEnum::cases())],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
