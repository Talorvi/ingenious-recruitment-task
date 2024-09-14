<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Services;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Application\Commands\ApproveInvoiceCommand;
use App\Modules\Invoices\Application\Commands\Bus\CommandBusInterface;
use App\Modules\Invoices\Application\Commands\RejectInvoiceCommand;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Exceptions\InvalidInvoiceStatusException;
use App\Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use App\Modules\Invoices\Infrastructure\Repositories\InvoiceRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

class InvoiceService
{
    private InvoiceRepositoryInterface $invoiceRepository;
    private CommandBusInterface $bus;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository, CommandBusInterface $bus)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->bus = $bus;
    }

    /**
     * @throws InvoiceNotFoundException
     */
    public function getInvoiceById(UuidInterface $id): Invoice
    {
        $invoice = $this->invoiceRepository->find($id);
        if (null === $invoice) {
            throw new InvoiceNotFoundException();
        }
        return $invoice;
    }

    /**
     * @throws InvalidInvoiceStatusException
     * @throws InvoiceNotFoundException
     */
    public function approveInvoice(UuidInterface $id): void
    {
        $invoice = $this->getInvoiceById($id);
        $this->ensureIsDraft($invoice);
        $command = new ApproveInvoiceCommand($invoice->getId());
        $this->bus->dispatch($command);
    }

    /**
     * @throws InvalidInvoiceStatusException
     * @throws InvoiceNotFoundException
     */
    public function rejectInvoice(UuidInterface $id): void
    {
        $invoice = $this->getInvoiceById($id);
        $this->ensureIsDraft($invoice);
        $command = new RejectInvoiceCommand($invoice->getId());
        $this->bus->dispatch($command);
    }

    /**
     * @throws InvalidInvoiceStatusException
     */
    private function ensureIsDraft(Invoice $invoice): void
    {
        if ($invoice->getStatus() !== StatusEnum::DRAFT) {
            throw new InvalidInvoiceStatusException();
        }
    }
}
