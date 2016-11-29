<?php

declare(strict_types = 1);

namespace Building\Infrastructure\Projector;

use Building\Domain\DomainEvent\NewBuildingWasRegistered;
use Building\Domain\DomainEvent\UserCheckedIntoBuilding;
use Building\Domain\DomainEvent\UserCheckedOutFromBuilding;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream\StreamName;

final class AddBuilding
{
    /**
     * @var EventStore
     */
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function __invoke(NewBuildingWasRegistered $newBuilding)
    {
        $json = json_encode([]);

        file_put_contents(__DIR__."/../../../public/incremental-{$newBuilding->aggregateId()}.json", $json);
    }
}
