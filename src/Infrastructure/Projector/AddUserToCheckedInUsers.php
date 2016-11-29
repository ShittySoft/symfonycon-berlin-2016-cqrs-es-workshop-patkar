<?php

declare(strict_types = 1);

namespace Building\Infrastructure\Projector;

use Building\Domain\DomainEvent\NewBuildingWasRegistered;
use Building\Domain\DomainEvent\UserCheckedIntoBuilding;
use Building\Domain\DomainEvent\UserCheckedOutFromBuilding;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream\StreamName;

final class AddUserToCheckedInUsers
{
    /**
     * @var EventStore
     */
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function __invoke(UserCheckedIntoBuilding $checkedIn)
    {
        $json = file_get_contents(__DIR__."/../../../public/incremental-{$checkedIn->aggregateId()}.json");

        $users = array_map('array_keys', json_decode($json, true));
        $users[$checkedIn->username()] = null;

        $json = json_encode(array_keys($users));

        file_put_contents(__DIR__."/../../../public/incremental-{$checkedIn->aggregateId()}.json", $json);
    }
}
