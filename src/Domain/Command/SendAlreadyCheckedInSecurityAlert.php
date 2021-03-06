<?php

declare(strict_types=1);

namespace Building\Domain\Command;

use Prooph\Common\Messaging\Command;
use Rhumsaa\Uuid\Uuid;

class SendAlreadyCheckedInSecurityAlert extends Command
{
    /**
     * @var Uuid
     */
    private $buildingId;

    /**
     * @var string
     */
    private $username;

    private function __construct(Uuid $buildingId, string $username)
    {
        $this->init();

        $this->buildingId = $buildingId;
        $this->username = $username;
    }

    public static function fromBuildingAndUsername(Uuid $buildingId, string $username) : self
    {
        return new self($buildingId, $username);
    }

    public function buildingId() : Uuid
    {
        return $this->buildingId;
    }

    public function username() : string
    {
        return $this->username;
    }

    public function payload() : array
    {
        return [
            'buildingId' => (string) $this->buildingId,
            'username' => $this->username,
        ];
    }

    protected function setPayload(array $payload)
    {
        $this->buildingId = Uuid::fromString($payload['buildingId']);
        $this->username = $payload['username'];
    }
}
