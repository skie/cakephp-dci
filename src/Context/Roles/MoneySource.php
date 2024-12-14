<?php
declare(strict_types=1);

namespace App\Context\Roles;

use App\Model\Entity\Account;

class MoneySource extends AbstractRole
{
    protected function validatePlayer($player): bool
    {
        return $player instanceof Account
            && method_exists($player, 'getBalance')
            && method_exists($player, 'setBalance');
    }

    public function withdraw(float $amount): void
    {
        $balance = $this->player->getBalance();
        if ($balance < $amount) {
            throw new \Exception('Insufficient funds');
        }
        $this->player->setBalance($balance - $amount);
    }
}
