<?php

declare(strict_types = 1);

namespace Building\Infrastructure\Listener;

use Building\Domain\Command\SendAlreadyCheckedInSecurityAlert;
use Building\Domain\DomainEvent\UserAlreadyCheckedIntoBuilding;
use Prooph\ServiceBus\CommandBus;

class RaiseAlreadyCheckedInSecurityBreach
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(UserAlreadyCheckedIntoBuilding $event)
    {
        $this->commandBus->dispatch(SendAlreadyCheckedInSecurityAlert::fromBuildingAndUsername(
            $event->buildingId(),
            $event->username()
        ));
    }
}
