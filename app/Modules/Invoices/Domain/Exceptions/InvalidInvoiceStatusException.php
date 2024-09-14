<?php

namespace App\Modules\Invoices\Domain\Exceptions;

use Exception;
use Throwable;

class InvalidInvoiceStatusException extends Exception
{
    public function __construct(string $message = "Only draft invoices can be processed", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
