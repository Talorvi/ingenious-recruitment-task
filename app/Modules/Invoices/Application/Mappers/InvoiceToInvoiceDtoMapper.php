<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Mappers;

use App\Modules\Invoices\Api\Dto\InvoiceDto;
use App\Modules\Invoices\Api\Dto\InvoiceLineItemDto;
use App\Modules\Invoices\Api\Dto\ProductDto;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Entities\InvoiceLineItem;

class InvoiceToInvoiceDtoMapper
{
    private const TAX_RATE = 0.0625;

    public static function map(Invoice $invoice): InvoiceDto
    {
        $companyDto = CompanyToCompanyDtoMapper::map($invoice->getCompany());
        $itemsDto = array_map([InvoiceLineItemToInvoiceLineItemDtoMapper::class, 'map'], $invoice->getLineItems());

        $totalAmount = 0;
        $taxAmount = 0;

        // Probably it's better to move it to a service
        foreach ($invoice->getLineItems() as $lineItem) {
            $linePrice = $lineItem->getProduct()->getPrice() * $lineItem->getQuantity();
            $taxAmount += $linePrice * self::TAX_RATE;
            $totalAmount += $linePrice;
        }

        $subtotalAmount = $totalAmount - $taxAmount;

        return new InvoiceDto(
            id: $invoice->getId(),
            number: $invoice->getNumber(),
            date: $invoice->getDate(),
            dueDate: $invoice->getDueDate(),
            company: $companyDto,
            status: $invoice->getStatus(),
            items: $itemsDto,
            totalAmount: $totalAmount,
            taxAmount: $taxAmount,
            subtotalAmount: $subtotalAmount
        );
    }
}
