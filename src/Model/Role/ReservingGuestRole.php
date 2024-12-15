<?php
declare(strict_types=1);

namespace App\Model\Role;

class ReservingGuestRole extends RoleBehavior
{
    protected $_defaultConfig = [
        'discounts' => [
            'standard' => 0,
            'silver' => 0.05,
            'gold' => 0.10,
            'platinum' => 0.15,
        ],
    ];

    public function implementedMethods(): array
    {
        return [
            'calculateDiscount' => 'calculateDiscount',
            'canMakeReservation' => 'canMakeReservation',
        ];
    }

    public function calculateDiscount(float $price): float
    {
        $loyaltyLevel = $this->getProperty('loyalty_level');
        $discountRate = $this->getConfig('discounts.' . $loyaltyLevel);

        return $price * $discountRate;
    }

    public function canMakeReservation(): bool
    {
        return true;
    }
}
