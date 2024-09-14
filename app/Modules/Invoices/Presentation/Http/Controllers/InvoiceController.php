<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Presentation\Http\Controllers;

use App\Infrastructure\Controller;
use App\Modules\Invoices\Application\Mappers\InvoiceToInvoiceDtoMapper;
use App\Modules\Invoices\Application\Services\InvoiceService;
use App\Modules\Invoices\Domain\Exceptions\InvalidInvoiceStatusException;
use App\Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    private InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function show(string $invoiceId): JsonResponse
    {
        try {
            $invoice = $this->invoiceService->getInvoiceById(Uuid::fromString($invoiceId));
            return response()->json(InvoiceToInvoiceDtoMapper::map($invoice));
        } catch (InvalidUuidStringException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (InvoiceNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function approve(string $invoiceId): JsonResponse
    {
        try {
            $this->invoiceService->approveInvoice(Uuid::fromString($invoiceId));
            return response()->json(['message' => 'Invoice is being processed']);
        } catch (InvalidUuidStringException|InvalidInvoiceStatusException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (InvoiceNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function reject(string $invoiceId): JsonResponse
    {
        try {
            $this->invoiceService->rejectInvoice(Uuid::fromString($invoiceId));
            return response()->json(['message' => 'Invoice is being processed']);
        } catch (InvalidUuidStringException|InvalidInvoiceStatusException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (InvoiceNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
