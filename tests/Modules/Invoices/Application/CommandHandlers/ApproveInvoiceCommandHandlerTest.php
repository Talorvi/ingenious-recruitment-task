<?php

namespace Tests\Modules\Invoices\Application\CommandHandlers;

use App\Modules\Invoices\Application\Commands\ApproveInvoiceCommand;
use App\Modules\Invoices\Application\Commands\CommandResult;
use App\Modules\Invoices\Application\Commands\Handlers\ApproveInvoiceCommandHandler;
use App\Modules\Invoices\Application\Facades\InvoiceFacadeInterface;
use App\Modules\Invoices\Domain\Exceptions\InvalidInvoiceStatusException;
use Mockery;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ApproveInvoiceCommandHandlerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function test_handle_returns_success_on_approval()
    {
        $invoiceId = Uuid::uuid4();
        $command = new ApproveInvoiceCommand($invoiceId);

        $invoiceFacade = Mockery::mock(InvoiceFacadeInterface::class);
        $invoiceFacade->shouldReceive('approveInvoice')
            ->with($invoiceId)
            ->andReturn(true);

        $handler = new ApproveInvoiceCommandHandler($invoiceFacade);

        $result = $handler->handle($command);

        $this->assertInstanceOf(CommandResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertEquals('Invoice approved successfully.', $result->message);
    }

    public function test_handle_returns_failure_when_approval_fails()
    {
        $invoiceId = Uuid::uuid4();
        $command = new ApproveInvoiceCommand($invoiceId);

        $invoiceFacade = Mockery::mock(InvoiceFacadeInterface::class);
        $invoiceFacade->shouldReceive('approveInvoice')
            ->with($invoiceId)
            ->andReturn(false);

        $handler = new ApproveInvoiceCommandHandler($invoiceFacade);

        $result = $handler->handle($command);

        $this->assertInstanceOf(CommandResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertEquals('Invoice approval failed.', $result->message);
    }

    public function test_handle_returns_failure_on_exception()
    {
        $invoiceId = Uuid::uuid4();
        $command = new ApproveInvoiceCommand($invoiceId);

        $invoiceFacade = Mockery::mock(InvoiceFacadeInterface::class);
        $invoiceFacade->shouldReceive('approveInvoice')
            ->with($invoiceId)
            ->andThrow(new InvalidInvoiceStatusException('Invalid status'));

        $handler = new ApproveInvoiceCommandHandler($invoiceFacade);

        $result = $handler->handle($command);

        $this->assertInstanceOf(CommandResult::class, $result);
        $this->assertFalse($result->success);
        $this->assertStringContainsString('Error during invoice approval: Invalid status', $result->message);
    }
}
