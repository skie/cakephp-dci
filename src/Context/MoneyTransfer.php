<?php
declare(strict_types=1);

namespace App\Context;

use App\Model\Entity\Account;
use App\Context\Roles\MoneySource;
use App\Context\Roles\MoneyDestination;

class MoneyTransfer
{
    private MoneySource $sourceRole;
    private MoneyDestination $destinationRole;
    private float $amount;

    public function __construct(Account $source, Account $destination, float $amount)
    {
        $this->sourceRole = new MoneySource();
        $this->sourceRole->bind($source);

        $this->destinationRole = new MoneyDestination();
        $this->destinationRole->bind($destination);

        $this->amount = $amount;
    }

    public function execute(): void
    {
        try {
            $this->sourceRole->withdraw($this->amount);
            $this->destinationRole->deposit($this->amount);
        } finally {
            $this->sourceRole->unbind();
            $this->destinationRole->unbind();
        }
    }

    public function __destruct()
    {
        $this->sourceRole->unbind();
        $this->destinationRole->unbind();
    }
}
