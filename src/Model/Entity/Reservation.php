<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * @property int $room_id
 * @property int $primary_guest_id
 * @property \Cake\I18n\DateTime $check_in
 * @property \Cake\I18n\DateTime $check_out
 * @property string $status
 * @property float $total_price
 * @property \App\Model\Entity\Room $room
 * @property \App\Model\Entity\Guest $primary_guest
 * @property \App\Model\Entity\Guest[] $reservation_guests
 * @property \App\Model\Entity\Guest[] $guests
 * @mixin \App\Model\Role\AuditableRole
 */
class Reservation extends RoleAwareEntity
{
    protected array $_accessible = [
        'id' => true,
        'room_id' => true,
        'primary_guest_id' => true,
        'check_in' => true,
        'check_out' => true,
        'status' => true,
        'total_price' => true,
        'room' => true,
        'primary_guest' => true,
        'reservation_guests' => true,
        'guests' => true,
    ];
}
