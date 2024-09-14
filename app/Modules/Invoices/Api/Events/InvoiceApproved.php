<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api\Events;

use App\Modules\Invoices\Api\Dto\InvoiceDto;

final readonly class InvoiceApproved
{
    public function __construct(
        public InvoiceDto $approvalDto
    ) {}
}
