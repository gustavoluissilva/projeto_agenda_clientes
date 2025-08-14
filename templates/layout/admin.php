<?= $this->Html->link(
    '<i class="fas fa-calendar-alt"></i> Ver Calendário de Agendamentos', // Ícone e Texto do botão
    ['controller' => 'Schedule', 'action' => 'calendarioAdmin'], // Para onde o link aponta
    [
        'class' => 'btn btn-primary', // Classes do Bootstrap para estilizar como um botão
        'escape' => false // IMPORTANTE: Permite que o ícone <i> seja renderizado
    ]
) ?>