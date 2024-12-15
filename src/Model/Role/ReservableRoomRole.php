<?php
declare(strict_types=1);

namespace App\Model\Role;

use Cake\Datasource\EntityInterface;
use Cake\I18n\DateTime;
use InvalidArgumentException;

class ReservableRoomRole extends RoleBehavior
{

    /**
     * @var \App\Model\Entity\Room
     */
    protected EntityInterface $_entity;

    protected $_defaultConfig = [
        'minAdvanceBookingDays' => 1,
        'maxAdvanceBookingDays' => 365,
    ];

    public function implementedMethods(): array
    {
        return [
            'isAvailableForDates' => 'isAvailableForDates',
            'canAccommodateGuests' => 'canAccommodateGuests',
            'calculatePrice' => 'calculatePrice',
            'markAsReserved' => 'markAsReserved',
        ];
    }

    public function isAvailableForDates(DateTime $checkIn, DateTime $checkOut): bool
    {
        if ($this->getProperty('status') !== 'available') {
            return false;
        }

        $today = new DateTime();
        $minDate = $today->modify('+' . $this->getConfig('minAdvanceBookingDays') . ' days');
        $maxDate = $today->modify('+' . $this->getConfig('maxAdvanceBookingDays') . ' days');

        if ($checkIn < $minDate || $checkIn > $maxDate) {
            return false;
        }

        if ($checkOut <= $checkIn) {
            return false;
        }

        $existingReservations = (array)$this->_entity->reservations;
        foreach ($existingReservations as $reservation) {
            if ($reservation->status === 'cancelled') {
                continue;
            }

            $result = max($checkIn, $reservation->check_in) <= min($checkOut, $reservation->check_out);
            if (!$result) {
                return false;
            }
        }

        return true;
    }

    public function canAccommodateGuests(int $guestCount): bool
    {
        return $guestCount > 0 && $guestCount <= $this->getProperty('capacity');
    }

    public function calculatePrice(DateTime $checkIn, DateTime $checkOut): float
    {
        $nights = $checkIn->diffInDays($checkOut);
        if ($nights < 1) {
            throw new InvalidArgumentException('Invalid date range');
        }

        return $this->getProperty('base_price') * $nights;
    }

    public function markAsReserved(): void
    {
        $this->_entity->logOperation('status_change', [
            'from' => 'available',
            'to' => 'occupied',
        ]);
    }
}
