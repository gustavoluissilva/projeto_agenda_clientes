<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Available> $availableRules
 * @var iterable<\App\Model\Entity\BlockedDate> $blockedDates // <-- CORRIGIDO AQUI
 */

// Um array para traduzir o número do dia da semana para texto
$weekDays = [
    0 => 'Domingo', 1 => 'Segunda-feira', 2 => 'Terça-feira', 3 => 'Quarta-feira',
    4 => 'Quinta-feira', 5 => 'Sexta-feira', 6 => 'Sábado'
];
?>

<h1>Gerenciamento de Disponibilidade</h1>


<div class="availability-index">
    <?= $this->Html->link('Definir Horário Semanal', ['action' => 'setWeekly'], ['class' => 'button']) ?>
    <?= $this->Html->link('Adicionar Bloqueio', ['action' => 'addBlockedDate'], ['class' => 'button']) ?>
    <?= $this->Html->link('Ver Calendário de Agendamentos',['controller' => 'Schedule', 'action' => 'calendarioAdmin'] // Para onde o link aponta
) ?>


    <hr>

    <h3>Horários de Trabalho Padrão</h3>
    <?php if (!$availableRules->isEmpty()): ?>
        <table>
            <thead>
                <tr>
                    <th>Dia da Semana</th>
                    <th>Início do Expediente</th>
                    <th>Fim do Expediente</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($availableRules as $rule): ?>
                <tr>
                    <td><?= $weekDays[$rule->week_day] ?></td>
                    <td><?= $rule->start_shift->format('H:i') ?></td>
                    <td><?= $rule->end_shift->format('H:i') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum horário de trabalho padrão definido.</p>
    <?php endif; ?>

    <hr>

    <h3>Bloqueios Agendados (Férias, Feriados, etc.)</h3>
    <?php if (!$blockedDates->isEmpty()): ?>
        <table>
            <thead>
                <tr>
                    <th>Motivo</th>
                    <th>Início do Bloqueio</th>
                    <th>Fim do Bloqueio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($blockedDates as $blockedDate): ?>
                <tr>
                    <td><?= h($blockedDate->reason) ?></td>
                    <td><?= $blockedDate->start_date->format('d/m/Y H:i') ?></td>
                    <td><?= $blockedDate->end_date->format('d/m/Y H:i') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum bloqueio agendado.</p>
    <?php endif; ?>
</div>