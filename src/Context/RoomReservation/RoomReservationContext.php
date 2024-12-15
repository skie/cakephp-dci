<?php
declare(strict_types=1);

namespace App\Context\RoomReservation;

use App\Model\Entity\Guest;
use App\Model\Entity\Room;
use App\Model\Entity\Reservation;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;

class RoomReservationContext
{
    private Room $room;
    private Guest $primaryGuest;
    private array $additionalGuests;
    private DateTime $checkIn;
    private DateTime $checkOut;
    private array $config;

    public function __construct(
        Room $room,
        Guest $primaryGuest,
        array $additionalGuests,
        DateTime $checkIn,
        DateTime $checkOut,
        array $config = []
    ) {
        $this->room = $room;
        $this->primaryGuest = $primaryGuest;
        $this->additionalGuests = $additionalGuests;
        $this->checkIn = $checkIn;
        $this->checkOut = $checkOut;
        $this->config = $config;
        $this->attachRoles();
    }

    private function attachRoles(): void
    {
        $this->room->addRole('Auditable');
        $this->room->addRole('ReservableRoom', $this->config['room'] ?? []);

        $this->primaryGuest->addRole('Auditable');
        $this->primaryGuest->addRole('ReservingGuest', $this->config['guest'] ?? []);
    }

    public function execute(): Reservation
    {
        try {
            $totalGuests = count($this->additionalGuests) + 1;
            if (!$this->room->isAvailableForDates($this->checkIn, $this->checkOut)) {
                throw new InvalidArgumentException('Room is not available for selected dates');
            }

            if (!$this->room->canAccommodateGuests($totalGuests)) {
                throw new InvalidArgumentException("Total number of guests ({$totalGuests}) exceeds room capacity ({$this->room->capacity})");
            }

            if (!$this->primaryGuest->canMakeReservation()) {
                throw new InvalidArgumentException('Guest cannot make reservations');
            }

            $basePrice = $this->room->calculatePrice($this->checkIn, $this->checkOut);
            $discount = $this->primaryGuest->calculateDiscount($basePrice);
            $finalPrice = $basePrice - $discount;

            $reservationsTable = TableRegistry::getTableLocator()->get('Reservations');

            /** @var Reservation $reservation */
            $reservation = $reservationsTable->newEntity([
                'room_id' => $this->room->id,
                'primary_guest_id' => $this->primaryGuest->id,
                'check_in' => $this->checkIn,
                'check_out' => $this->checkOut,
                'status' => 'confirmed',
                'total_price' => $finalPrice,
                'reservation_guests' => array_map(
                    fn($guest) => ['guest_id' => $guest->id],
                    $this->additionalGuests
                ),
            ]);
            $reservation->addRole('Auditable');

            $reservationsTable->getConnection()->transactional(function() use ($reservation, $reservationsTable) {
                if (!$reservationsTable->save($reservation)) {
                    throw new InvalidArgumentException('Could not save reservation');
                }

                $reservation->logOperation('reservation_created', [
                    'room_number' => $this->room->number,
                    'guest_name' => $this->primaryGuest->name,
                    'check_in' => $this->checkIn->format('Y-m-d'),
                    'check_out' => $this->checkOut->format('Y-m-d'),
                    'total_price' => $reservation->total_price,
                    'additional_guests' => count($this->additionalGuests),
                ]);

                return $reservation;
            });

            return $reservation;
        } finally {
            $this->detachRoles();
        }
    }

    private function detachRoles(): void
    {
        $this->room->removeRole('ReservableRoom');
        $this->room->removeRole('Auditable');

        $this->primaryGuest->removeRole('ReservingGuest');
        $this->primaryGuest->removeRole('Auditable');
    }
}
