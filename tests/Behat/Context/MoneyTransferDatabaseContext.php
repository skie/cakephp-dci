<?php
declare(strict_types=1);

namespace App\Test\Behat\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class MoneyTransferDatabaseContext extends BaseContext
{
    private $accounts;
    private $auditLogs;

    /**
     * @BeforeScenario
     */
    public function initializeTest(BeforeScenarioScope $scope): void
    {
        $this->initialize();
        $this->accounts = TableRegistry::getTableLocator()->get('ComplexAccounts');
        $this->auditLogs = TableRegistry::getTableLocator()->get('AuditLogs');
        $this->cleanDatabase();
    }

    /**
     * @BeforeScenario
     */
    public function cleanDatabase()
    {
        $this->auditLogs->deleteAll([]);
        $this->accounts->deleteAll([]);
    }

    /**
     * @Given the following accounts exist:
     */
    public function theFollowingAccountsExist($table)
    {
        foreach ($table->getHash() as $row) {
            $row['is_frozen'] = filter_var($row['is_frozen'], FILTER_VALIDATE_BOOLEAN);
            $account = $this->accounts->newEntity($row);
            $this->accounts->save($account);
        }
    }

    /**
     * @Then account :id should have balance of :balance
     */
    public function accountShouldHaveBalanceOf($id, $balance)
    {
        $account = $this->accounts->get($id);
        TestCase::assertEquals(
            (float)$balance,
            (float)$account->balance,
            "Account balance mismatch"
        );
    }

    /**
     * @Then an audit log should exist with:
     */
    public function anAuditLogShouldExistWith($table)
    {
        foreach ($table->getHash() as $row) {
            $exists = $this->auditLogs->exists($row);
            TestCase::assertTrue(
                $exists,
                "Expected audit log not found: " . json_encode($row)
            );
        }
    }
}
