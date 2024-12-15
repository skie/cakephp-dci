<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Reservation> $reservations
 */
?>
<div class="reservations index content">
    <h3><?= __('Reservations') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= __('Room') ?></th>
                    <th><?= __('Guest') ?></th>
                    <th><?= __('Check In') ?></th>
                    <th><?= __('Check Out') ?></th>
                    <th><?= __('Status') ?></th>
                    <th><?= __('Total Price') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?= h($reservation->room->number) ?></td>
                    <td><?= h($reservation->primary_guest->name) ?></td>
                    <td><?= h($reservation->check_in->format('Y-m-d')) ?></td>
                    <td><?= h($reservation->check_out->format('Y-m-d')) ?></td>
                    <td><?= h($reservation->status) ?></td>
                    <td><?= $this->Number->currency($reservation->total_price) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $reservation->id]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="button-group">
        <?= $this->Html->link(__('New Reservation'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    </div>
</div>
