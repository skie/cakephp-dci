<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * ComplexAccounts seed.
 */
class ComplexAccountsSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'balance' => 1000.00,
                'account_type' => 'savings',
                'status' => 'active',
                'is_frozen' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'balance' => 5000.00,
                'account_type' => 'checking',
                'status' => 'active',
                'is_frozen' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'balance' => 25000.00,
                'account_type' => 'business',
                'status' => 'active',
                'is_frozen' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'balance' => 100.00,
                'account_type' => 'savings',
                'status' => 'inactive',
                'is_frozen' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'balance' => 15000.00,
                'account_type' => 'investment',
                'status' => 'active',
                'is_frozen' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'balance' => 500.00,
                'account_type' => 'checking',
                'status' => 'pending',
                'is_frozen' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'balance' => 75000.00,
                'account_type' => 'business',
                'status' => 'active',
                'is_frozen' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'balance' => 3000.00,
                'account_type' => 'savings',
                'status' => 'active',
                'is_frozen' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'balance' => 250.00,
                'account_type' => 'checking',
                'status' => 'suspended',
                'is_frozen' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'balance' => 50000.00,
                'account_type' => 'investment',
                'status' => 'active',
                'is_frozen' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('complex_accounts');
        $table->insert($data)->save();
    }
}
