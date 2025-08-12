<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BlockedDate $blockedDate
 */
?>
<h1>Adicionar Bloqueio/Exceção</h1>
<div class="blocked-dates form content">
    <?= $this->Form->create($blockedDate) ?>
    <fieldset>
        <legend>Preencha os dados do período a ser bloqueado</legend>
        <?php
            echo $this->Form->control('reason', ['label' => 'Motivo do Bloqueio']);
            // Usa os novos nomes de coluna
            echo $this->Form->control('start_date', ['label' => 'Início do Bloqueio']);
            echo $this->Form->control('end_date', ['label' => 'Fim do Bloqueio']);
        ?>
    </fieldset>
    <?= $this->Form->button('Salvar Bloqueio') ?>
    <?= $this->Form->end() ?>
</div>