<?php
/**
 * @var \App\View\AppView $this  // A view do CakePHP
 * @var array $currentRules      // Regras atuais enviadas pelo controller
 */

// Lista de dias da semana (chave = número do dia, valor = nome exibido)
$weekDays = [
    1 => 'Segunda-feira', 2 => 'Terça-feira', 3 => 'Quarta-feira',
    4 => 'Quinta-feira', 5 => 'Sexta-feira', 6 => 'Sábado', 0 => 'Domingo'
];

// Organiza as regras atuais por número do dia para fácil acesso
$rulesByDay = [];
foreach ($currentRules as $rule) {
    $rulesByDay[$rule->week_day] = $rule;
}
?>

<h1>Definir Horário Semanal Padrão</h1>

<!-- Início do formulário, envia via POST para a action setWeekly -->
<?= $this->Form->create(null, ['url' => ['action' => 'setWeekly']]) ?>

<fieldset>
    <legend>Marque os dias de trabalho e defina os horários</legend>

    <!-- Loop em todos os dias da semana -->
    <?php foreach ($weekDays as $dayNum => $dayName): ?>
        <?php
            // Verifica se já existe regra para este dia
            $isChecked = isset($rulesByDay[$dayNum]);
            
            // Define valores iniciais (se houver)
            $startValue = $isChecked ? $rulesByDay[$dayNum]->start_shift->format('H:i') : '';
            $endValue   = $isChecked ? $rulesByDay[$dayNum]->end_shift->format('H:i') : '';
        ?>

        <div class="day-rule">
            <!-- Campo oculto para enviar o número do dia -->
            <?= $this->Form->hidden("days.{$dayNum}.week_day", ['value' => $dayNum]) ?>
            
            <!-- Checkbox para ativar/desativar o dia -->
            <?= $this->Form->checkbox("days.{$dayNum}.active", ['checked' => $isChecked, 'id' => "day-{$dayNum}"]) ?>
            <label for="day-<?= $dayNum ?>"><?= $dayName ?></label>
            
            <!-- Campo para horário de início -->
            <?= $this->Form->control("days.{$dayNum}.start_shift", [
                'type'  => 'time',
                'label' => 'Início',
                'value' => $startValue
            ]) ?>

            <!-- Campo para horário de fim -->
            <?= $this->Form->control("days.{$dayNum}.end_shift", [
                'type'  => 'time',
                'label' => 'Fim',
                'value' => $endValue
            ]) ?>
        </div>
        <hr>
    <?php endforeach; ?>
</fieldset>

<!-- Botão para enviar o formulário -->
<?= $this->Form->button('Salvar Horários') ?>

<!-- Fecha o formulário -->
<?= $this->Form->end() ?>
