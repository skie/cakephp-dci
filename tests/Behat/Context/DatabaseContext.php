<?php
declare(strict_types=1);

namespace App\Test\Behat\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Cake\ORM\TableRegistry;

class DatabaseContext extends BaseContext
{
    private $tables = [
        'audit_logs',
        'reservation_guests',
        'reservations',
        'guests',
        'rooms',
    ];

    /**
     * @BeforeScenario
     */
    public function initializeTest(BeforeScenarioScope $scope): void
    {
        $this->initialize();
        $this->clearDatabase();
    }

    /**
     * @BeforeScenario
     */
    public function clearDatabase(): void
    {
        $connection = TableRegistry::getTableLocator()
            ->get('Reservations')
            ->getConnection();

        $connection->execute('PRAGMA foreign_keys = OFF');
        foreach ($this->tables as $tableName) {
            TableRegistry::getTableLocator()->get($tableName)->deleteAll([]);
        }
        $connection->execute('PRAGMA foreign_keys = ON');
    }

    /**
     * @Given the following rooms exist:
     */
    public function theFollowingRoomsExist(TableNode $rooms): void
    {
        $roomsTable = TableRegistry::getTableLocator()->get('Rooms');
        $headers = $rooms->getRow(0);
        foreach ($rooms->getRows() as $i => $room) {
            if ($i === 0) {
                $i++;
                continue;
            }
            $room = array_combine($headers, $room);
            $entity = $roomsTable->newEntity($room);
            $roomsTable->save($entity);
        }
    }

    /**
     * @Given the following guests exist:
     */
    public function theFollowingGuestsExist(TableNode $guests)
    {
        $guestsTable = TableRegistry::getTableLocator()->get('Guests');
        $headers = $guests->getRow(0);
        foreach ($guests->getRows() as $i => $guest) {
            if ($i === 0) {
                $i++;
                continue;
            }
            $guest = array_combine($headers, $guest);
            $entity = $guestsTable->newEntity($guest);
            $guestsTable->save($entity);
        }
    }

    /**
     * @Given the following reservations exist:
     */
    public function theFollowingReservationsExist(TableNode $reservations)
    {
        $reservationsTable = TableRegistry::getTableLocator()->get('Reservations');
        $headers = $reservations->getRow(0);
        foreach ($reservations->getRows() as $i => $reservation) {
            if ($i === 0) {
                $i++;
                continue;
            }
            $reservation = array_combine($headers, $reservation);
            $entity = $reservationsTable->newEntity($reservation);
            $reservationsTable->save($entity);
        }
    }
}
