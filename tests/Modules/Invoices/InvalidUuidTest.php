<?php

namespace Tests\Modules\Invoices;

use Tests\TestCase;

class InvalidUuidTest extends TestCase
{
    public function test_show_invoice_with_invalid_uuid_returns_400()
    {
        $invalidUuid = 'invalid-uuid';

        $response = $this->getJson("/api/invoices/{$invalidUuid}");

        $response->assertStatus(400)
            ->assertJsonStructure(['error']);
    }

    public function test_approve_invoice_with_invalid_uuid_returns_400()
    {
        $invalidUuid = 'invalid-uuid';

        $response = $this->postJson("/api/invoices/{$invalidUuid}/approve");

        $response->assertStatus(400)
            ->assertJsonStructure(['error']);
    }
}
