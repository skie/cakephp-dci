<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Account extends Entity
{
    protected array $_accessible = [
        'balance' => true,
        'name' => true,
    ];

    protected float $balance;

    public function getBalance(): float
    {
        return $this->get('balance');
    }

    public function setBalance(float $amount): void
    {
        $this->set('balance', $amount);
    }
}
