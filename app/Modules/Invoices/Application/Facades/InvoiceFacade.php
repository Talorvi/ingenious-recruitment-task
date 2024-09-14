<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Facades;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Invoices\Api\Dto\InvoiceDto;
use App\Modules\Invoices\Api\Events\InvoiceApproved;
use App\Modules\Invoices\Api\Events\InvoiceRejected;
use App\Modules\Invoices\Application\Mappers\InvoiceToApprovalDtoMapper;
use App\Modules\Invoices\Application\Mappers\InvoiceToInvoiceDtoMapper;
use App\Modules\Invoices\Domain\Exceptions\InvalidInvoiceStatusException;
use App\Modules\Invoices\Infrastructure\Repositories\InvoiceRepositoryInterface;
use Illuminate\Contracts\Events\Dispatcher;
use LogicException;
use Ramsey\Uuid\UuidInterface;

final readonly class InvoiceFacade implements InvoiceFacadeInterface
{
    private InvoiceRepositoryInterface $invoiceRepository;
    private ApprovalFacadeInterface $approvalFacade;
    private Dispatcher $dispatcher;

    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        ApprovalFacadeInterface $approvalFacade,
        Dispatcher $dispatcher
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->approvalFacade = $approvalFacade;
        $this->dispatcher = $dispatcher;
    }

    public function getInvoiceById(UuidInterface $id): ?InvoiceDto
    {
        $invoice = $this->invoiceRepository->find($id);
        return $invoice ? InvoiceToInvoiceDtoMapper::map($invoice) : null;
    }

    public function approveInvoice(UuidInterface $id): bool
    {
        return $this->processInvoice($id, StatusEnum::APPROVED, InvoiceApproved::class);
    }

    public function rejectInvoice(UuidInterface $id): bool
    {
        return $this->processInvoice($id, StatusEnum::REJECTED, InvoiceRejected::class);
    }

    private function processInvoice(UuidInterface $id, StatusEnum $newStatus, string $eventClass): bool
    {
        $invoice = $this->invoiceRepository->find($id);
        if (!$invoice) {
            return false;
        }

        $approvalDto = InvoiceToApprovalDtoMapper::map($invoice);
        try {
            switch ($newStatus) {
                case StatusEnum::APPROVED:
                    $this->approvalFacade->approve($approvalDto);
                    $invoice->approve();
                    break;
                case StatusEnum::REJECTED:
                    $this->approvalFacade->reject($approvalDto);
                    $invoice->reject();
                    break;
                default:
                    throw new LogicException("Invalid status: {$newStatus->value}");
            }
            $this->invoiceRepository->save($invoice);
            $invoiceDto = InvoiceToInvoiceDtoMapper::map($invoice);
            $this->dispatcher->dispatch(new $eventClass($invoiceDto));
            return true;
        } catch (LogicException|InvalidInvoiceStatusException $e) {
            return false;
        }
    }
}
