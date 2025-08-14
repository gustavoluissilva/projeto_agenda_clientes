<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Service $service
 * @var array $availableSlots
 */
?>
<div class="booking-select-time content">
    <h1>Faça seu Agendamento</h1>
    <p class="flow-step">Passo 2 de 3: Escolha a data e o horário para "<?= h($service->name) ?>".</p>
    <hr>

    <?php if (!empty($availableSlots)): ?>
        <?php foreach ($availableSlots as $date => $slots): ?>
            <h3><?= (new \DateTime($date))->format('d \d\e F \d\e Y') ?></h3>
            
            <div class="time-slots">
                <?php foreach ($slots as $slot): ?>
                    <?= $this->Html->link(
                        // Texto do botão (ex: 10:30)
                        $slot->format('H:i'),
                        // URL para a página de confirmação (Passo 3)
                        ['action' => 'confirm', $service->id, $slot->format('Y-m-d-H-i-s')],
                        // Classe CSS para estilização
                        ['class' => 'button button-outline']
                    ) ?>
                <?php endforeach; ?>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-slots-message">
            <p><strong>Desculpe, não há horários disponíveis para este serviço nos próximos 30 dias.</strong></p>
            <p>Por favor, tente outro serviço ou verifique novamente mais tarde.</p>
        </div>
    <?php endif; ?>

    <br>
    
    <?= $this->Html->link('Voltar e escolher outro serviço', ['action' => 'index'], ['class' => 'button']) ?>
</div>