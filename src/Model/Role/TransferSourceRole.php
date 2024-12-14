<?php
declare(strict_types=1);

namespace App\Model\Role;

use App\Model\Entity\ComplexAccount;
use Cake\Datasource\EntityInterface;

class TransferSourceRole extends RoleBehavior
{

    /**
     * @var ComplexAccount
     */
    protected EntityInterface $_entity;

    protected $_defaultConfig = [
        'field' => 'balance',
        'minimumBalance' => 0
    ];

    public function implementedMethods(): array
    {
        return [
            'withdraw' => 'withdraw',
            'canWithdraw' => 'canWithdraw'
        ];
    }

    public function withdraw(float $amount): void
    {
        if (!$this->canWithdraw($amount)) {
            throw new \InvalidArgumentException('Cannot withdraw: insufficient funds or invalid amount');
        }

        $balanceField = $this->getConfig('field');
        $currentBalance = $this->getProperty($balanceField);

        $this->_entity->logOperation('pre_withdrawal', [
            'amount' => $amount,
            'current_balance' => $currentBalance
        ]);

        $this->setProperty($balanceField, $currentBalance - $amount);

        $this->_entity->logOperation('post_withdrawal', [
            'amount' => $amount,
            'new_balance' => $this->getProperty($balanceField)
        ]);
    }

    public function canWithdraw(float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }

        $balanceField = $this->getConfig('field');
        $minimumBalance = $this->getConfig('minimumBalance');

        return $this->getProperty($balanceField) - $amount >= $minimumBalance &&
               $this->getProperty('status') === 'active' &&
               !$this->getProperty('is_frozen');
    }
}
