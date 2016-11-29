<?php

declare(strict_types = 1);

namespace Building\Infrastructure\Projector;

use Building\Domain\DomainEvent\NewBuildingWasRegistered;
use Building\Domain\DomainEvent\UserCheckedIntoBuilding;
use Building\Domain\DomainEvent\UserCheckedOutFromBuilding;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream\StreamName;

final class RemoveUserFromCheckedInUsers
{
    /**
     * @var EventStore
     */
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function __invoke(UserCheckedOutFromBuilding $checkedOut)
    {
        $json = file_get_contents(__DIR__."/../../../public/incremental-{$checkedOut->aggregateId()}.json");

        $users = array_flip(json_decode($json, true));
        unset($users[$checkedOut->username()]);

        $json = json_encode(array_flip($users));

        file_put_contents(__DIR__."/../../../public/incremental-{$checkedOut->aggregateId()}.json", $json);
    }
}
