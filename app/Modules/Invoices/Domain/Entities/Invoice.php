<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entities;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Exceptions\InvalidInvoiceStatusException;
use Ramsey\Uuid\UuidInterface;

class Invoice
{
    private UuidInterface $id;
    private string $number;
    private \DateTimeInterface $date;
    private \DateTimeInterface $dueDate;
    private Company $company;
    private array $lineItems;
    private StatusEnum $status;

    public function __construct(
        UuidInterface $id,
        string $number,
        \DateTimeInterface $date,
        \DateTimeInterface $dueDate,
        Company $company,
        array $lineItems,
        StatusEnum $status
    ) {
        $this->id = $id;
        $this->number = $number;
        $this->date = $date;
        $this->dueDate = $dueDate;
        $this->company = $company;
        $this->lineItems = $lineItems;
        $this->status = $status;
    }

    public function getId(): UuidInterface {
        return $this->id;
    }

    public function getNumber(): string {
        return $this->number;
    }

    public function getDate(): \DateTimeInterface {
        return $this->date;
    }

    public function getDueDate(): \DateTimeInterface {
        return $this->dueDate;
    }

    public function getCompany(): Company {
        return $this->company;
    }

    public function getLineItems(): array {
        return $this->lineItems;
    }

    public function getStatus(): StatusEnum {
        return $this->status;
    }

    /**
     * @throws InvalidInvoiceStatusException
     */
    public function approve(): void
    {
        $this->validate();
        $this->status = StatusEnum::APPROVED;
    }

    /**
     * @throws InvalidInvoiceStatusException
     */
    public function reject(): void
    {
        $this->validate();
        $this->status = StatusEnum::REJECTED;
    }

    /**
     * @throws InvalidInvoiceStatusException
     */
    private function validate(): void
    {
        if ($this->status !== StatusEnum::DRAFT) {
            throw new InvalidInvoiceStatusException();
        }
    }
}
