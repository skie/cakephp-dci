<?php
declare(strict_types=1);

namespace App\Model\Role;

use Cake\Datasource\EntityInterface;

class TransferDestinationRole extends RoleBehavior
{
    /**
     * @var ComplexAccount
     */
    protected EntityInterface $_entity;

    protected $_defaultConfig = [
        'field' => 'balance',
        'maxDeposit' => null
    ];

    public function implementedMethods(): array
    {
        return [
            'deposit' => 'deposit',
            'canDeposit' => 'canDeposit'
        ];
    }

    public function deposit(float $amount): void
    {
        if (!$this->canDeposit($amount)) {
            throw new \InvalidArgumentException('Cannot deposit: invalid amount or limit exceeded');
        }

        $balanceField = $this->getConfig('field');
        $currentBalance = $this->getProperty($balanceField);

        $this->_entity->logOperation('pre_deposit', [
            'amount' => $amount,
            'current_balance' => $currentBalance
        ]);

        $this->setProperty($balanceField, $currentBalance + $amount);

        $this->_entity->logOperation('post_deposit', [
            'amount' => $amount,
            'new_balance' => $this->getProperty($balanceField)
        ]);
    }

    public function canDeposit(float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }

        $maxDeposit = $this->getConfig('maxDeposit');
        return ($maxDeposit === null || $amount <= $maxDeposit) &&
               $this->getProperty('status') === 'active' &&
               !$this->getProperty('is_frozen');
    }
}
