<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Mappers;

use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Invoices\Domain\Entities\Invoice;

class InvoiceToApprovalDtoMapper
{
    public static function map(Invoice $invoice): ApprovalDto
    {
        return new ApprovalDto(
            id: $invoice->getId(),
            status: $invoice->getStatus(),
            entity: 'invoice'
        );
    }
}
