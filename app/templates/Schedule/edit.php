<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Schedule $schedule
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $schedule->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $schedule->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Schedule'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="schedule form content">
            <?= $this->Form->create($schedule) ?>
            <fieldset>
                <legend><?= __('Edit Schedule') ?></legend>
                <?php
                    echo $this->Form->control('id_users');
                    echo $this->Form->control('id_services');
                    echo $this->Form->control('date_start');
                    echo $this->Form->control('date_end');
                    echo $this->Form->control('status');
                    echo $this->Form->control('observation');
                    echo $this->Form->control('schedule_date');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
