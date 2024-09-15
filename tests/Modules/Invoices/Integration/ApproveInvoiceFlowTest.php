<?php

namespace Tests\Modules\Invoices\Integration;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Infrastructure\Models\EloquentCompany;
use App\Modules\Invoices\Infrastructure\Models\EloquentInvoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;

class ApproveInvoiceFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_approve_invoice_successfully()
    {
        $company = $this->createCompany();

        $invoice = $this->createInvoice([
            'status' => StatusEnum::DRAFT->value,
            'company_id' => $company->id,
        ]);

        $response = $this->postJson("/api/invoices/{$invoice->id}/approve");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Invoice is being processed']);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => StatusEnum::APPROVED->value,
        ]);
    }

    public function test_cannot_approve_non_draft_invoice()
    {
        $company = $this->createCompany();

        $invoice = $this->createInvoice([
            'status' => StatusEnum::APPROVED->value,
            'company_id' => $company->id,
        ]);

        $response = $this->postJson("/api/invoices/{$invoice->id}/approve");

        $response->assertStatus(400)
            ->assertJson(['error' => 'Only draft invoices can be processed']);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => StatusEnum::APPROVED->value,
        ]);
    }

    private function createCompany(): EloquentCompany
    {
        $company = new EloquentCompany([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Test Company',
            'street' => '123 Test St',
            'city' => 'Test City',
            'zip' => '12345',
            'phone' => '555-1234',
            'email' => 'test@example.com',
        ]);
        $company->save();

        return $company;
    }

    private function createInvoice(array $attributes = []): EloquentInvoice
    {
        $invoice = new EloquentInvoice(array_merge([
            'id' => Uuid::uuid4()->toString(),
            'number' => 'INV-' . rand(1000, 9999),
            'date' => now(),
            'due_date' => now()->addDays(30),
            'status' => StatusEnum::DRAFT->value,
            'company_id' => null,
        ], $attributes));
        $invoice->save();

        return $invoice;
    }
}
