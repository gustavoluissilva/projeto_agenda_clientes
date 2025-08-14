<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Service> $services
 */
?>

<div class="booking-index content">
    <h1>Faça seu Agendamento</h1>
    <p class="flow-step">Passo 1 de 3: Escolha o serviço desejado.</p>
    <hr>

    <?php if (!$services->isEmpty()): ?>
        <div class="services-list">
            <?php foreach ($services as $service): ?>
                <div class="service-item">
                    <h3><?= h($service->name) ?></h3>
                    <p><?= h($service->description) ?></p>
                    <ul>
                        <li><strong>Duração:</strong> <?= $service->time_spend ?> minutos</li>
                        <li><strong>Preço:</strong> R$ <?= number_format((float)$service->price, 2, ',', '.') ?></li>
                    </ul>
                    
                    <?= $this->Html->link(
                        'Selecionar este Serviço',
                        ['action' => 'selectTime', $service->id],
                        ['class' => 'button button-primary']
                    ) ?>
                </div>
                <hr>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Nenhum serviço disponível para agendamento no momento. Por favor, volte mais tarde.</p>
    <?php endif; ?>
</div>