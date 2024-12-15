<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * @method void withdraw(float $amount)
 * @method bool canWithdraw(float $amount)
 * @method void deposit(float $amount)
 * @method bool canDeposit(float $amount)
 * @method void logOperation(string $operation, array $data)
 * @method void notify(string $type, array $data)
 */
class ComplexAccount extends RoleAwareEntity
{
    protected array $_accessible = [
        'id' => true,
        'balance' => true,
        'account_type' => true,
        'status' => true,
        'is_frozen' => true,
        'created' => true,
        'modified' => true
    ];
}
