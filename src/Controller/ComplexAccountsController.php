<?php
declare(strict_types=1);

namespace App\Controller;

use App\Context\MoneyTransfer\MoneyTransferContext as MoneyTransfer;

class ComplexAccountsController extends AppController
{

    public $ComplexAccounts;

    public function initialize(): void
    {
        parent::initialize();
        $this->ComplexAccounts = $this->fetchTable('ComplexAccounts');

    }

    public function transfer()
    {
        if ($this->request->is(['post'])) {
            try {
                $source = $this->ComplexAccounts->get($this->request->getData('source_id'));
                $destination = $this->ComplexAccounts->get($this->request->getData('destination_id'));
                $amount = (float)$this->request->getData('amount');

                $transfer = new MoneyTransfer($this->ComplexAccounts, $source, $destination, $amount);

                $transfer->execute();

                $this->Flash->success('Transfer completed successfully');

            } catch (\InvalidArgumentException $e) {
                $this->Flash->error($e->getMessage());
            }
            $this->redirect(['action' => 'transfer']);
        }

        $this->set('complexAccounts', $this->ComplexAccounts->find('list', valueField: ['account_type', 'id'])->all());
    }
}
