<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAuditLogs extends BaseMigration
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
        $table = $this->table('audit_logs');

        $table->addColumn('model', 'string', [
            'limit' => 100,
            'null' => false,
            'comment' => 'Model/table name that was audited'
        ])
        ->addColumn('foreign_key', 'integer', [
            'null' => false,
            'comment' => 'Related record ID'
        ])
        ->addColumn('operation', 'string', [
            'limit' => 50,
            'null' => false,
            'comment' => 'Type of operation performed'
        ])
        ->addColumn('data', 'json', [
            'null' => true,
            'comment' => 'Additional operation data'
        ])
        ->addColumn('created', 'datetime', [
            'null' => true,
        ])
        ->addIndex(['model', 'foreign_key'])
        ->addIndex(['operation'])
        ->addIndex(['created'])
        ->create();
    }
}
