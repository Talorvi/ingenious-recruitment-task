<?php

namespace Tests\Modules\Invoices\Integration;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Infrastructure\Models\EloquentCompany;
use App\Modules\Invoices\Infrastructure\Models\EloquentInvoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;

class RejectInvoiceFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_approve_invoice_successfully()
    {
        $company = $this->createCompany();
        $billedCompany = $this->createBilledCompany();

        $invoice = $this->createInvoice([
            'status' => StatusEnum::DRAFT->value,
            'company_id' => $company->id,
            'billed_company_id' => $billedCompany->id
        ]);

        $response = $this->postJson("/api/invoices/{$invoice->id}/reject");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Invoice is being processed']);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => StatusEnum::REJECTED->value,
        ]);
    }

    public function test_cannot_approve_non_draft_invoice()
    {
        $company = $this->createCompany();
        $billedCompany = $this->createBilledCompany();

        $invoice = $this->createInvoice([
            'status' => StatusEnum::REJECTED->value,
            'company_id' => $company->id,
            'billed_company_id' => $billedCompany->id
        ]);

        $response = $this->postJson("/api/invoices/{$invoice->id}/reject");

        $response->assertStatus(400)
            ->assertJson(['error' => 'Only draft invoices can be processed']);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => StatusEnum::REJECTED->value,
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

    private function createBilledCompany(): EloquentCompany
    {
        $company = new EloquentCompany([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Billed Company',
            'street' => '456 Billed St',
            'city' => 'Billing City',
            'zip' => '67890',
            'phone' => '555-5678',
            'email' => 'billing@example.com'
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
            'billed_company_id' => null,
        ], $attributes));
        $invoice->save();

        return $invoice;
    }
}
