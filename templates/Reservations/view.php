<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reservation $reservation
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('All Reservations'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Reservation'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="reservations view content">
            <h3><?= __('Reservation Details') ?></h3>
            <table>
                <tr>
                    <th><?= __('Room Number') ?></th>
                    <td><?= h($reservation->room->number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Room Type') ?></th>
                    <td><?= h($reservation->room->type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Primary Guest') ?></th>
                    <td><?= h($reservation->primary_guest->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Check In') ?></th>
                    <td><?= h($reservation->check_in->format('Y-m-d')) ?></td>
                </tr>
                <tr>
                    <th><?= __('Check Out') ?></th>
                    <td><?= h($reservation->check_out->format('Y-m-d')) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= h($reservation->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Price') ?></th>
                    <td><?= $this->Number->currency($reservation->total_price) ?></td>
                </tr>
            </table>

            <?php if (!empty($reservation->guests)): ?>
            <div class="related">
                <h4><?= __('Additional Guests') ?></h4>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Phone') ?></th>
                        </tr>
                        <?php foreach ($reservation->guests as $guest): ?>
                        <tr>
                            <td><?= h($guest->name) ?></td>
                            <td><?= h($guest->email) ?></td>
                            <td><?= h($guest->phone) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
