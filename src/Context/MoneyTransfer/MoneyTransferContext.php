<?php
declare(strict_types=1);

namespace App\Context\MoneyTransfer;

use App\Model\Entity\ComplexAccount;
use App\Model\Table\ComplexAccountsTable;

class MoneyTransferContext
{
    private readonly ComplexAccount $source;
    private readonly ComplexAccount $destination;
    private readonly float $amount;
    private readonly ComplexAccountsTable $ComplexAccounts;

    public function __construct(
        ComplexAccountsTable $ComplexAccounts,
        ComplexAccount $source,
        ComplexAccount $destination,
        float $amount,
        array $config = []
    ) {
        $this->source = $source;
        $this->destination = $destination;
        $this->amount = $amount;
        $this->ComplexAccounts = $ComplexAccounts;
        $this->attachRoles($config);
    }

    private function attachRoles(array $config): void
    {
        $this->source->addRole('Auditable');
        $this->source->addRole('TransferSource', $config['source'] ?? []);

        $this->destination->addRole('Auditable');
        $this->destination->addRole('TransferDestination', $config['destination'] ?? []);
    }

    public function execute(): void
    {
        try {
            $this->ComplexAccounts->getConnection()->transactional(function() {
                if (!$this->source->canWithdraw($this->amount)) {
                    throw new \InvalidArgumentException('Source cannot withdraw this amount');
                }

                if (!$this->destination->canDeposit($this->amount)) {
                    throw new \InvalidArgumentException('Destination cannot accept this deposit');
                }

                $this->source->withdraw($this->amount);
                $this->destination->deposit($this->amount);

                // This code will not able to work! Methods not attached not available, and logic errors does not possible to perform in context.
                // $this->source->deposit($this->amount);
                // $this->destination->withdraw($this->amount);

                $this->ComplexAccounts->saveMany([
                    $this->source,
                    $this->destination
                ]);
            });
        } finally {
            $this->detachRoles();
        }
    }

    private function detachRoles(): void
    {
        $this->source->removeRole('TransferSource');
        $this->source->removeRole('Auditable');

        $this->destination->removeRole('TransferDestination');
        $this->destination->removeRole('Auditable');
    }
}
