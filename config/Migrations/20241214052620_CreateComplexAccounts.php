<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateComplexAccounts extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('complex_accounts');

        $table->addColumn('balance', 'decimal', [
            'precision' => 10,
            'scale' => 2,
            'default' => 0,
            'null' => false,
        ])
        ->addColumn('account_type', 'string', [
            'limit' => 50,
            'null' => false,
            'default' => 'standard'
        ])
        ->addColumn('status', 'string', [
            'limit' => 20,
            'null' => false,
            'default' => 'active'
        ])
        ->addColumn('is_frozen', 'boolean', [
            'null' => false,
            'default' => false
        ])
        ->addColumn('created', 'datetime', [
            'null' => true,
        ])
        ->addColumn('modified', 'datetime', [
            'null' => true,
        ])
        ->addIndex(['account_type', 'status'])
        ->create();
    }
}
