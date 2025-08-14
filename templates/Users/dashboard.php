<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Schedule> $schedules // A lista de agendamentos que o controller enviou
 * @var \Authorization\IdentityInterface $identity // Informações do usuário logado
 */

// Pega o objeto do usuário logado para exibir o nome
$user = $this->request->getAttribute('identity');
?>

<div class="users-dashboard content">
    <h3>Olá, <?= h($user->names) ?>!</h3>
    <p>Bem-vindo(a) ao seu painel. Aqui estão seus próximos agendamentos:</p>
    
    <hr>

    <?php if (!$schedules->isEmpty()): ?>
        <table>
            <thead>
                <tr>
                    <th>Serviço</th>
                    <th>Data e Hora</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td><?= h($schedule->service->name) ?></td>
                    <td><?= $schedule->date_start->format('d/m/Y \à\s H:i') ?></td>
                    <td><?= h($schedule->status) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Você ainda não tem nenhum agendamento futuro.</p>
    <?php endif; ?>

    <br>

    <?= $this->Html->link('Fazer um Novo Agendamento', ['controller' => 'Booking', 'action' => 'index'], ['class' => 'button button-primary']) ?>
</div>