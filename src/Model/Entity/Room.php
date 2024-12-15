<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $number
 * @property string $type
 * @property int $capacity
 * @property float $base_price
 * @property string $status
 * @property \App\Model\Entity\Reservation[] $reservations
 *
 * @method bool isAvailableForDates(DateTime $checkIn, DateTime $checkOut)
 * @method bool canAccommodateGuests(int $numberOfGuests)
 * @method float calculatePrice(DateTime $checkIn, DateTime $checkOut)
 * @method void logOperation(string $operation, array $data)
 */
class Room extends RoleAwareEntity
{
    protected array $_accessible = [
        'id' => true,
        'number' => true,
        'type' => true,
        'capacity' => true,
        'base_price' => true,
        'status' => true,
        'reservations' => true,
    ];
}
