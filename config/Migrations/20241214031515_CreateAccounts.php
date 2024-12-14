<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAccounts extends BaseMigration
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
        $table = $this->table('accounts');
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('balance', 'float', [
            'default' => 0,
            'null' => false,
        ])
        ->create();
    }
}
