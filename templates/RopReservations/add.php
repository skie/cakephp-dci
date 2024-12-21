<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reservation $reservation
 * @var \Cake\Collection\CollectionInterface|array $rooms
 * @var \Cake\Collection\CollectionInterface|array $guests
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Reservations'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="reservations form content">
            <?= $this->Form->create(null) ?>
            <fieldset>
                <legend><?= __('New Reservation') ?></legend>
                <?php
                    echo $this->Form->control('room_id', [
                        'options' => $rooms,
                        'empty' => true,
                        'label' => 'Room'
                    ]);
                    echo $this->Form->control('primary_guest_id', [
                        'options' => $guests,
                        'empty' => true,
                        'label' => 'Primary Guest'
                    ]);
                    echo $this->Form->control('additional_guest_ids', [
                        'options' => $guests,
                        'multiple' => true,
                        'label' => 'Additional Guests'
                    ]);
                    echo $this->Form->control('check_in', [
                        'type' => 'date',
                        'min' => date('Y-m-d', strtotime('+1 day')),
                        'max' => date('Y-m-d', strtotime('+1 year'))
                    ]);
                    echo $this->Form->control('check_out', [
                        'type' => 'date',
                        'min' => date('Y-m-d', strtotime('+2 days')),
                        'max' => date('Y-m-d', strtotime('+1 year'))
                    ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php $this->start('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInField = document.querySelector('input[name="check_in"]');
    const checkOutField = document.querySelector('input[name="check_out"]');

    checkInField.addEventListener('change', function() {
        const minCheckOut = new Date(this.value);
        minCheckOut.setDate(minCheckOut.getDate() + 1);
        checkOutField.min = minCheckOut.toISOString().split('T')[0];

        if (new Date(checkOutField.value) <= new Date(this.value)) {
            checkOutField.value = minCheckOut.toISOString().split('T')[0];
        }
    });
});
</script>
<?php $this->end(); ?>
