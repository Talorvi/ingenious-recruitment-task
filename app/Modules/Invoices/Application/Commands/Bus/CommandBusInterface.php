<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Commands\Bus;

use App\Modules\Invoices\Application\Commands\CommandInterface;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
    public function map(array $map): void;
}
