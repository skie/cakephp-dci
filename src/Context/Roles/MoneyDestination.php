<?php
declare(strict_types=1);
namespace App\Context\Roles;

use App\Model\Entity\Account;

class MoneyDestination extends AbstractRole
{
    protected function validatePlayer($player): bool
    {
        return $player instanceof Account
            && method_exists($player, 'getBalance')
            && method_exists($player, 'setBalance');
    }

    public function deposit(float $amount): void
    {
        $currentBalance = $this->player->getBalance();
        $this->player->setBalance($currentBalance + $amount);
    }
}
