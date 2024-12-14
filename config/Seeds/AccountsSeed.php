<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Accounts seed.
 */
class AccountsSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Savings Account',
                'balance' => 1000.00,
            ],
            [
                'name' => 'Checking Account',
                'balance' => 500.00,
            ],
            [
                'name' => 'Business Account',
                'balance' => 2500.00,
            ],
        ];

        $table = $this->table('accounts');
        $table->insert($data)->save();
    }
}
