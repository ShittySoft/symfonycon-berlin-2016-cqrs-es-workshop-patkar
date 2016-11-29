<?php

declare(strict_types=1);

namespace Building\Domain\Aggregate;

use Building\Domain\DomainEvent\NewBuildingWasRegistered;
use Building\Domain\DomainEvent\UserCheckedIntoBuilding;
use Building\Domain\Exception\UserAlreadyCheckedIn;
use Prooph\EventSourcing\AggregateRoot;
use Rhumsaa\Uuid\Uuid;

final class Building extends AggregateRoot
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $checkedInUsers = [];

    public static function new(string $name) : self
    {
        $self = new self();

        $self->recordThat(NewBuildingWasRegistered::occur(
            (string) Uuid::uuid4(),
            [
                'name' => $name
            ]
        ));

        return $self;
    }

    public function checkInUser(string $username)
    {
        if (in_array($username, $this->checkedInUsers, true)) {
            throw new UserAlreadyCheckedIn($this->uuid, $this->name, $username);
        }

        $this->recordThat(UserCheckedIntoBuilding::fromBuildingAndUsername(
            $this->uuid,
            $username
        ));
    }

    public function checkOutUser(string $username)
    {
        // @TODO to be implemented
    }

    protected function whenNewBuildingWasRegistered(NewBuildingWasRegistered $event)
    {
        $this->uuid = $event->uuid();
        $this->name = $event->name();
    }

    protected function whenUserCheckedIntoBuilding(UserCheckedIntoBuilding $event)
    {
        $this->checkedInUsers[] = $event->username();
    }

    /**
     * {@inheritDoc}
     */
    protected function aggregateId() : string
    {
        return (string) $this->uuid;
    }
}
