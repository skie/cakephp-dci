<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateRoomReservationsTables extends BaseMigration
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
        $this->table('rooms')
            ->addColumn('number', 'string', ['limit' => 10])
            ->addColumn('type', 'string', ['limit' => 50])
            ->addColumn('capacity', 'integer')
            ->addColumn('base_price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'available'])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->addIndex(['number'], ['unique' => true])
            ->create();

        $this->table('guests')
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('phone', 'string', ['limit' => 20])
            ->addColumn('loyalty_level', 'string', ['limit' => 20, 'default' => 'standard'])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->addIndex(['email'], ['unique' => true])
            ->create();

        $this->table('reservations')
            ->addColumn('room_id', 'integer')
            ->addColumn('primary_guest_id', 'integer')
            ->addColumn('check_in', 'date')
            ->addColumn('check_out', 'date')
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending'])
            ->addColumn('total_price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->addForeignKey('room_id', 'rooms', 'id')
            ->addForeignKey('primary_guest_id', 'guests', 'id')
            ->create();

        $this->table('reservation_guests')
            ->addColumn('reservation_id', 'integer')
            ->addColumn('guest_id', 'integer')
            ->addColumn('created', 'datetime')
            ->addForeignKey('reservation_id', 'reservations', 'id')
            ->addForeignKey('guest_id', 'guests', 'id')
            ->create();
    }
}
