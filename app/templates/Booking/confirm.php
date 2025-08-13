<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Service $service
 * @var \DateTime $scheduleTime
 */
?>
<div class="booking-confirm content">
    <h1>Confirme seu Agendamento</h1>
    <p class="flow-step">Passo 3 de 3: Revise os detalhes e finalize.</p>
    <hr>

    <div class="booking-summary">
        <h3>Detalhes do Serviço</h3>
        <p><strong>Serviço:</strong> <?= h($service->name) ?></p>
        <p><strong>Duração:</strong> <?= $service->time_spend ?> minutos</p>
        <p><strong>Preço:</strong> R$ <?= number_format((float)$service->price, 2, ',', '.') ?></p>
    </div>

    <hr>

    <div class="booking-time">
        <h3>Data e Hora Escolhidos</h3>
        <p><strong>Dia:</strong> <?= $scheduleTime->format('d/m/Y') ?> (<?= $this->Time->i18nFormat($scheduleTime, 'eeee') ?>)</p>
        <p><strong>Horário:</strong> <?= $scheduleTime->format('H:i') ?></p>
    </div>

    <hr>

    <?= $this->Form->create(null, ['url' => ['action' => 'save']]) ?>
        <?php
            // Campos escondidos que o controller 'save' precisa para criar o agendamento
            echo $this->Form->hidden('id_services', ['value' => $service->id]);
            echo $this->Form->hidden('date_start', ['value' => $scheduleTime->format('Y-m-d H:i:s')]);
        ?>
        
        <fieldset>
            <?php
            // Campo opcional para o cliente adicionar uma observação
            echo $this->Form->control('observation', [
                'type' => 'textarea',
                'label' => 'Observação (opcional): Tem alguma alergia ou pedido especial?',
                'rows' => 3,
            ]);
            ?>
        </fieldset>

        <?= $this->Form->button('Confirmar e Finalizar Agendamento', ['class' => 'button-primary']) ?>
    <?= $this->Form->end() ?>

    <br>
    
    <?= $this->Html->link(
        'Voltar e escolher outro horário',
        ['action' => 'selectTime', $service->id],
        ['class' => 'button button-outline']
    ) ?>
</div>