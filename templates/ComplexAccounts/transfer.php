<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ComplexAccount $complexAccount
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
        </div>
    </aside>
    <div class="column column-80">
        <div class="complexAccounts form content">
            <?= $this->Form->create(null) ?>
            <fieldset>
                <legend><?= __('Transfer') ?></legend>
                <?php
                    echo $this->Form->control('source_id', [
                        'type' => 'select',
                        'options' => $complexAccounts,
                    ]);
                    echo $this->Form->control('destination_id', [
                        'type' => 'select',
                        'options' => $complexAccounts,
                    ]);
                    echo $this->Form->control('amount');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
