<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api\Dto;

use App\Domain\Enums\StatusEnum;
use Ramsey\Uuid\UuidInterface;

class InvoiceDto
{
    public UuidInterface $id;
    public string $number;
    public \DateTimeInterface $date;
    public \DateTimeInterface $dueDate;
    public CompanyDto $company;
    public StatusEnum $status;
    public array $items;
    public float $totalAmount;
    public float $taxAmount;
    public float $subtotalAmount;

    public function __construct(
        UuidInterface $id,
        string $number,
        \DateTimeInterface $date,
        \DateTimeInterface $dueDate,
        CompanyDto $company,
        StatusEnum $status,
        array $items,
        float $totalAmount,
        float $taxAmount,
        float $subtotalAmount
    ) {
        $this->subtotalAmount = $subtotalAmount;
        $this->taxAmount = $taxAmount;
        $this->totalAmount = $totalAmount;
        $this->items = $items;
        $this->status = $status;
        $this->company = $company;
        $this->dueDate = $dueDate;
        $this->date = $date;
        $this->number = $number;
        $this->id = $id;
    }
}
