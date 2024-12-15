Feature: Money Transfer
  In order to move money between accounts
  As an account holder
  I need to be able to transfer funds between accounts

  Background:
    Given the following accounts exist:
      | id | balance  | account_type | status | is_frozen |
      | 1  | 1000.00  | checking     | active | false     |
      | 2  | 500.00   | savings      | active | false     |
      | 3  | 200.00   | checking     | active | true      |
      | 4  | 300.00   | deposit_only | active | false     |

  Scenario: Successful transfer between active accounts
    When I transfer "200.00" from account "1" to account "2"
    Then account "1" should have balance of "800.00"
    And account "2" should have balance of "700.00"
    And an audit log should exist with:
      | foreign_key | operation       |
      | 1           | pre_withdrawal  |
      | 1           | post_withdrawal |
      | 2           | pre_deposit     |
      | 2           | post_deposit    |

  Scenario: Cannot transfer from frozen account
    When I try to transfer "100.00" from account "3" to account "2"
    Then I should get an error "Source cannot withdraw this amount"
    And account "3" should have balance of "200.00"
    And account "2" should have balance of "500.00"

  Scenario: Cannot transfer more than available balance
    When I try to transfer "1200.00" from account "1" to account "2"
    Then I should get an error "Source cannot withdraw this amount"
    And account "1" should have balance of "1000.00"
    And account "2" should have balance of "500.00"

  Scenario: Cannot transfer negative amount
    When I try to transfer "-100.00" from account "1" to account "2"
    Then I should get an error "Source cannot withdraw this amount"
    And account "1" should have balance of "1000.00"
    And account "2" should have balance of "500.00"

  Scenario: Cannot transfer to invalid account type
    When I try to transfer "100.00" from account "2" to account "1"
    Then the transfer should complete successfully
    And account "2" should have balance of "400.00"
    And account "1" should have balance of "1100.00"
