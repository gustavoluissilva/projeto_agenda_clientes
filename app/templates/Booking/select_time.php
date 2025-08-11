<h1>Escolha um Horário para <?= h($service->name) ?></h1>

<?php if (!empty($availableSlots)): ?>
    <?php foreach ($availableSlots as $date => $slots): ?>
        <h3><?= (new \DateTime($date))->format('d/m/Y') ?></h3>
        <div class="time-slots">
            <?php foreach ($slots as $slot): ?>
                <?= $this->Html->link(
                    $slot->format('H:i'),
                    // A URL para a página de confirmação
                    ['action' => 'confirm', $service->id, $slot->format('Y-m-d-H-i-s')],
                    ['class' => 'button button-outline'] // Uma classe para estilizar
                ) ?>
            <?php endforeach; ?>
        </div>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>Desculpe, não há horários disponíveis para este serviço nos próximos 30 dias. Por favor, tente outro serviço ou volte mais tarde.</p>
<?php endif; ?>