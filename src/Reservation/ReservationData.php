<?php
declare(strict_types=1);

namespace App\Reservation;

use Cake\I18n\DateTime;

class ReservationData
{
    public function __construct(
        public readonly array $room,
        public readonly array $primaryGuest,
        public readonly array $additionalGuests,
        public readonly DateTime $checkIn,
        public readonly DateTime $checkOut,
        private array $state = []
    ) {}

    public function withState(string $key, mixed $value): self
    {
        $clone = clone $this;
        $clone->state[$key] = $value;
        return $clone;
    }

    public function getState(string $key): mixed
    {
        return $this->state[$key] ?? null;
    }
}
