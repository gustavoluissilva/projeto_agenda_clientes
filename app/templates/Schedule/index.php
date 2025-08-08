<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Schedule> $schedule
 */
?>
<div class="schedule index content">
    <?= $this->Html->link(__('New Schedule'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Schedule') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('id_users') ?></th>
                    <th><?= $this->Paginator->sort('id_services') ?></th>
                    <th><?= $this->Paginator->sort('date_start') ?></th>
                    <th><?= $this->Paginator->sort('date_end') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <th><?= $this->Paginator->sort('schedule_date') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedule as $schedule): ?>
                <tr>
                    <td><?= $this->Number->format($schedule->id) ?></td>
                    <td><?= $this->Number->format($schedule->id_users) ?></td>
                    <td><?= $this->Number->format($schedule->id_services) ?></td>
                    <td><?= h($schedule->date_start) ?></td>
                    <td><?= h($schedule->date_end) ?></td>
                    <td><?= h($schedule->status) ?></td>
                    <td><?= h($schedule->schedule_date) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $schedule->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $schedule->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $schedule->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $schedule->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>