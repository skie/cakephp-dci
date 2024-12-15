<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $loyalty_level
 * @property \App\Model\Entity\Reservation[] $reservations
 *
 * @method float calculateDiscount(float $price)
 * @method bool canMakeReservation()
 * @method void logOperation(string $operation, array $data)
 */
class Guest extends RoleAwareEntity
{
    protected array $_accessible = [
        'id' => true,
        'name' => true,
        'email' => true,
        'phone' => true,
        'loyalty_level' => true,
        'reservations' => true,
    ];
}
