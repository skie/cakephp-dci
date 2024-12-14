<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Account $account
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
        </div>
    </aside>
    <div class="column column-80">
        <div class="accounts form content">
            <?= $this->Form->create(null) ?>
            <fieldset>
                <legend><?= __('Transfer') ?></legend>
                <?php
                    echo $this->Form->control('source_id', [
                        'type' => 'select',
                        'options' => $accounts,
                    ]);
                    echo $this->Form->control('destination_id', [
                        'type' => 'select',
                        'options' => $accounts,
                    ]);
                    echo $this->Form->control('amount');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
