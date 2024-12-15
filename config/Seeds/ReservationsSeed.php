<?php
declare(strict_types=1);

use Migrations\BaseSeed;
use Cake\I18n\DateTime;

/**
 * Reservations seed.
 */
class ReservationsSeed extends BaseSeed
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
        $rooms = [
            [
                'number' => '101',
                'type' => 'standard',
                'capacity' => 2,
                'base_price' => 100.00,
                'status' => 'available',
                'created' => new DateTime(),
                'modified' => new DateTime(),
            ],
            [
                'number' => '201',
                'type' => 'suite',
                'capacity' => 4,
                'base_price' => 200.00,
                'status' => 'available',
                'created' => new DateTime(),
                'modified' => new DateTime(),
            ],
            [
                'number' => '301',
                'type' => 'deluxe',
                'capacity' => 3,
                'base_price' => 150.00,
                'status' => 'available',
                'created' => new DateTime(),
                'modified' => new DateTime(),
            ],
            [
                'number' => '401',
                'type' => 'penthouse',
                'capacity' => 6,
                'base_price' => 300.00,
                'status' => 'available',
                'created' => new DateTime(),
                'modified' => new DateTime(),
            ],
        ];

        $this->table('rooms')->insert($rooms)->save();

        $guests = [
            [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'loyalty_level' => 'gold',
                'created' => new DateTime(),
                'modified' => new DateTime(),
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'phone' => '0987654321',
                'loyalty_level' => 'silver',
                'created' => new DateTime(),
                'modified' => new DateTime(),
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'phone' => '5555555555',
                'loyalty_level' => 'bronze',
                'created' => new DateTime(),
                'modified' => new DateTime(),
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'phone' => '1112223333',
                'loyalty_level' => 'platinum',
                'created' => new DateTime(),
                'modified' => new DateTime(),
            ],
        ];

        $this->table('guests')->insert($guests)->save();

        $room201Id = $this->fetchRow("SELECT id FROM rooms WHERE number = '201'")['id'];
        $janeDoeId = $this->fetchRow("SELECT id FROM guests WHERE email = 'jane@example.com'")['id'];

        $reservations = [
            [
                'room_id' => $room201Id,
                'primary_guest_id' => $janeDoeId,
                'check_in' => '2024-06-01',
                'check_out' => '2024-06-05',
                'status' => 'confirmed',
                'total_price' => 800.00,
                'created' => new DateTime(),
                'modified' => new DateTime(),
            ],
        ];

        $this->table('reservations')->insert($reservations)->save();

        $reservationId = $this->fetchRow("SELECT id FROM reservations ORDER BY id DESC LIMIT 1")['id'];
        $bobWilsonId = $this->fetchRow("SELECT id FROM guests WHERE email = 'bob@example.com'")['id'];

        $reservationGuests = [
            [
                'reservation_id' => $reservationId,
                'guest_id' => $bobWilsonId,
                'created' => new DateTime(),
            ],
        ];

        $this->table('reservation_guests')->insert($reservationGuests)->save();
    }
}
