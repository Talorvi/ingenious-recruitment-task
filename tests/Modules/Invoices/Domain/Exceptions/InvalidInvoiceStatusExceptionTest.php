<?php

namespace Tests\Modules\Invoices\Domain\Exceptions;

use App\Modules\Invoices\Domain\Exceptions\InvalidInvoiceStatusException;
use Tests\TestCase;

class InvalidInvoiceStatusExceptionTest extends TestCase
{
    public function test_exception_has_correct_message_and_code()
    {
        $message = 'Only draft invoices can be processed';
        $code = 400;

        $exception = new InvalidInvoiceStatusException();

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    public function test_exception_can_set_custom_message_and_code()
    {
        $message = 'Custom error message';
        $code = 422;

        $exception = new InvalidInvoiceStatusException($message, $code);

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }
}

