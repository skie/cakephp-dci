<?php
declare(strict_types=1);

namespace App\Test\Behat\Context;

use App\Context\RoomReservation\RoomReservationContext;
use App\Model\Entity\Guest;
use App\Model\Entity\Room;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use PHPUnit\Framework\Assert;

class ReservationContext extends RawMinkContext implements Context
{
    private ?Guest $authenticatedGuest = null;
    private ?Room $selectedRoom = null;
    private array $additionalGuests = [];
    private ?string $lastError = null;
    private ?float $totalPrice = null;
    private ?array $reservationDates = null;
    private ?array $lastLoggedOperation = null;

    /**
     * @Given I am authenticated as :name
     */
    public function iAmAuthenticatedAs(string $name): void
    {
        $this->authenticatedGuest = TableRegistry::getTableLocator()
            ->get('Guests')
            ->find()
            ->where(['name' => $name])
            ->firstOrFail();
    }

    /**
     * @When I try to reserve room :number for the following stay:
     */
    public function iTryToReserveRoomForTheFollowingStay(string $number, TableNode $table): void
    {
        $this->selectedRoom = TableRegistry::getTableLocator()
            ->get('Rooms')
            ->find()
            ->where(['number' => $number])
            ->contain(['Reservations'])
            ->firstOrFail();

        $this->reservationDates = $table->getRowsHash();
    }

    /**
     * @When I add :name as an additional guest
     */
    public function iAddAsAnAdditionalGuest(string $name): void
    {
        $guest = TableRegistry::getTableLocator()
            ->get('Guests')
            ->find()
            ->where(['name' => $name])
            ->firstOrFail();

        $this->additionalGuests[] = $guest;
    }

    /**
     * Execute the reservation with all collected information
     */
    private function executeReservation(): void
    {
        if (!$this->selectedRoom || !$this->reservationDates || !$this->authenticatedGuest) {
            return;
        }

        try {
            $context = new RoomReservationContext(
                $this->selectedRoom,
                $this->authenticatedGuest,
                $this->additionalGuests,
                new DateTime($this->reservationDates['check_in']),
                new DateTime($this->reservationDates['check_out'])
            );

            $reservation = $context->execute();
            $this->totalPrice = (float)$reservation->total_price;
            $this->lastError = null;
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
        }
    }

    /**
     * @Then the reservation should be confirmed
     */
    public function theReservationShouldBeConfirmed(): void
    {
        $this->executeReservation();

        if ($this->lastError !== null) {
            throw new \Exception("Expected reservation to be confirmed but got error: {$this->lastError}");
        }
    }

    /**
     * @Then the total price should be :price
     */
    public function theTotalPriceShouldBe(string $price): void
    {
        $this->executeReservation();

        $expectedPrice = (float)str_replace('"', '', $price);
        if ($this->totalPrice !== $expectedPrice) {
            throw new \Exception(
                "Expected price to be {$expectedPrice} but got {$this->totalPrice}"
            );
        }
    }

    /**
     * @Then I should see an error :message
     */
    public function iShouldSeeAnError(string $message): void
    {
        $this->executeReservation();

        if ($this->lastError === null) {
            throw new \Exception("Expected error but none was thrown");
        }
        if (strpos($this->lastError, $message) === false) {
            throw new \Exception(
                "Expected error message '{$message}' but got '{$this->lastError}'"
            );
        }
    }

    /**
     * @Then I should receive a confirmation email
     */
    public function iShouldReceiveAConfirmationEmail(): void
    {
    }

    /**
     * @When I visit the reservation page
     */
    public function iVisitTheReservationPage(): void
    {
        $this->visitPath('/reservations');
    }

    /**
     * @When I fill in the reservation form
     */
    public function iFillInTheReservationForm(TableNode $table): void
    {
        $this->visitPath('/reservations/add');

        foreach ($table->getRowsHash() as $field => $value) {
            $this->getSession()->getPage()->fillField($field, $value);
        }
    }

    /**
     * @When I submit the reservation form
     */
    public function iSubmitTheReservationForm(): void
    {
        $this->getSession()->getPage()->pressButton('Submit');
    }

    /**
     * @Then the following operation should be logged:
     */
    public function theFollowingOperationShouldBeLogged(TableNode $table): void
    {
        $expectedLog = $table->getRowsHash();

        $AuditLogs = TableRegistry::getTableLocator()->get('AuditLogs');
        $lastOperation = $AuditLogs->find()->orderByDesc('created')->first();

        Assert::assertNotNull($lastOperation, 'No operation was logged');
        Assert::assertEquals($expectedLog['model'], $lastOperation->model);
        Assert::assertEquals($expectedLog['operation'], $lastOperation->operation);

        $expectedData = [];
        foreach (explode(', ', $expectedLog['data']) as $pair) {
            [$key, $value] = explode('=', $pair);
            $expectedData[$key] = $value;
        }

        Assert::assertEquals($expectedData, json_decode($lastOperation->data, true));
    }
}
