<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Commands\Bus;

use App\Modules\Invoices\Application\Commands\CommandInterface;
use Illuminate\Bus\Dispatcher;

class IlluminateCommandBus implements CommandBusInterface
{
    private Dispatcher $bus;

    public function __construct(Dispatcher $bus) {
        $this->bus = $bus;
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->bus->dispatch($command);
    }

    public function map(array $map): void
    {
        $this->bus->map($map);
    }
}
