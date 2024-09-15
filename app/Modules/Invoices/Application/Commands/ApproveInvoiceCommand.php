<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Commands;

use Ramsey\Uuid\UuidInterface;

class ApproveInvoiceCommand implements CommandInterface
{
    public function __construct(
        public UuidInterface $invoiceId
    ) {}
}
