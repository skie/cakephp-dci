<?php
declare(strict_types=1);

namespace App\Controller;

use App\Context\MoneyTransfer;

class AccountsController extends AppController
{

    public $Accounts;

    public function initialize(): void
    {
        parent::initialize();
        $this->Accounts = $this->fetchTable('Accounts');

    }

    public function transfer()
    {
        if ($this->request->is(['post'])) {
            $sourceAccount = $this->Accounts->get($this->request->getData('source_id'));
            $destinationAccount = $this->Accounts->get($this->request->getData('destination_id'));
            $amount = (float)$this->request->getData('amount');
            try {
                $context = new MoneyTransfer($sourceAccount, $destinationAccount, $amount);
                $context->execute();

                $this->Accounts->saveMany([
                    $sourceAccount,
                    $destinationAccount
                ]);

                $this->Flash->success('Transfer completed successfully');
            } catch (\Exception $e) {
                $this->Flash->error($e->getMessage());
            }
            return $this->redirect(['action' => 'transfer']);
        }

        $this->set('accounts', $this->Accounts->find('list', valueField: ['name'])->all());
    }
}
