<?php
declare(strict_types=1);

namespace App\Reservation;

use Cake\Mailer\Mailer;
use ROP\Railway;
use Cake\ORM\TableRegistry;

class ReservationOperations
{
    public static function validateAvailability(ReservationData $data): Railway
    {
        $reservationsTable = TableRegistry::getTableLocator()->get('Reservations');
        $existingReservation = $reservationsTable->find()
            ->where([
                'room_id' => $data->room['id'],
                'status !=' => 'cancelled',
            ])
            ->where(function ($exp) use ($data) {
                return $exp->or([
                    function ($exp) use ($data) {
                        return $exp->between('check_in', $data->checkIn, $data->checkOut);
                    },
                    function ($exp) use ($data) {
                        return $exp->between('check_out', $data->checkIn, $data->checkOut);
                    }
                ]);
            })
            ->first();
        if ($existingReservation) {
            return Railway::fail("Room is not available for selected dates");
        }

        $totalGuests = count($data->additionalGuests) + 1;
        if ($totalGuests > $data->room['capacity']) {
            return Railway::fail(
                "Total number of guests ({$totalGuests}) exceeds room capacity ({$data->room['capacity']})"
            );
        }

        $basePrice = $data->room['base_price'] * $data->checkIn->diffInDays($data->checkOut);
        $discount = match($data->primaryGuest['loyalty_level']) {
            'gold' => 0.1,
            'silver' => 0.05,
            default => 0
        };

        $finalPrice = $basePrice * (1 - $discount);

        return Railway::of($data->withState('total_price', $finalPrice));
    }

    public static function createReservation(ReservationData $data): ReservationData
    {
        $reservationsTable = TableRegistry::getTableLocator()->get('Reservations');

        $reservation = $reservationsTable->newEntity([
            'room_id' => $data->room['id'],
            'primary_guest_id' => $data->primaryGuest['id'],
            'check_in' => $data->checkIn,
            'check_out' => $data->checkOut,
            'status' => 'confirmed',
            'total_price' => $data->getState('total_price'),
            'reservation_guests' => array_map(
                fn($guest) => ['guest_id' => $guest['id']],
                $data->additionalGuests
            ),
        ]);

        if (!$reservationsTable->save($reservation)) {
            throw new \RuntimeException('Could not save reservation');
        }

        return $data->withState('reservation_id', $reservation->id);
    }

    public static function logReservation(ReservationData $data): ReservationData
    {
        $Reservations = TableRegistry::getTableLocator()->get('Reservations');
        $Reservations->logOperation(
            $Reservations,
            $data->getState('reservation_id'),
            'reservation_created',
            [
                'reservation_id' => $data->getState('reservation_id'),
                'room_number' => $data->room['number'],
                'guest_name' => $data->primaryGuest['name'],
                'check_in' => $data->checkIn->format('Y-m-d'),
                'check_out' => $data->checkOut->format('Y-m-d'),
                'total_price' => $data->getState('total_price'),
                'additional_guests' => count($data->additionalGuests),
            ]
        );

        return $data;
    }

    public static function sendConfirmationEmail(ReservationData $data): Railway
    {

        $result = rand(0,10);

        return $result > 2 ? Railway::of($data) : Railway::fail('Failed to send confirmation email');
    }

    public static function updateGuestLoyaltyPoints(ReservationData $data): ReservationData
    {
        $guestsTable = TableRegistry::getTableLocator()->get('Guests');
        $guest = $guestsTable->get($data->primaryGuest['id']);

        $guest->loyalty_points += $data->getState('loyalty_points');

        if (!$guestsTable->save($guest)) {
            throw new \RuntimeException('Failed to update guest loyalty points');
        }

        return $data;
    }
}
