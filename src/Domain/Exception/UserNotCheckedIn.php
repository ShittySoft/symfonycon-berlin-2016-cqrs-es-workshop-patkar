<?php

declare(strict_types = 1);

namespace Building\Domain\Exception;

use Rhumsaa\Uuid\Uuid;

class UserNotCheckedIn extends \DomainException
{
    public function __construct(Uuid $buildingId, string $buildingName, string $username)
    {
        parent::__construct(sprintf(
            'User "%s" is not checked into building "%s" (%s)',
            $username, $buildingName, $buildingId
        ));
    }
}
