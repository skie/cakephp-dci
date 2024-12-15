<?php
declare(strict_types=1);

namespace App\Test\Behat\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use App\Context\MoneyTransfer\MoneyTransferContext as MoneyTransfer;
use Behat\Behat\Context\Context;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class MoneyTransferContext extends BaseContext
{
    private $accounts;
    private $lastError;

    /**
     * @BeforeScenario
     */
    public function initializeTest(BeforeScenarioScope $scope): void
    {
        $this->initialize();
        $this->accounts = TableRegistry::getTableLocator()->get('ComplexAccounts');
    }

    /**
     * @When I transfer :amount from account :sourceId to account :destId
     */
    public function iTransferFromAccountToAccount($amount, $sourceId, $destId)
    {
        $source = $this->accounts->get($sourceId);
        $destination = $this->accounts->get($destId);

        $transfer = new MoneyTransfer($this->accounts, $source, $destination, (float)$amount);
        $this->accounts->getConnection()->transactional(function() use ($transfer) {
            $transfer->execute();
        });
    }

    /**
     * @When I try to transfer :amount from account :sourceId to account :destId
     */
    public function iTryToTransferFromAccountToAccount($amount, $sourceId, $destId)
    {
        try {
            $this->iTransferFromAccountToAccount($amount, $sourceId, $destId);
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
        }
    }

    /**
     * @Then I should get an error :message
     */
    public function iShouldGetAnError($message)
    {
        TestCase::assertEquals(
            $message,
            $this->lastError,
            "Expected error message not received"
        );
    }

    /**
     * @Then the transfer should complete successfully
     */
    public function theTransferShouldCompleteSuccessfully()
    {
        TestCase::assertNull(
            $this->lastError,
            "Transfer failed with error: {$this->lastError}"
        );
    }
}
