<?php
/**
 * @var \App\View\AppView $this
 * @var string $agendamentosJson
 * @var \App\Model\Entity\Schedule[] $proximosAgendamentos
 * @var \App\Model\Entity\Schedule[] $agendamentosPassados // Nova variável
 * @var \Cake\I18n\FrozenDate $hoje
 */
?>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>

<style>
    /* ... (mesmos estilos de antes) ... */
    #calendario { max-width: 1100px; margin: 20px auto; font-size: 14px; }
    .lista-agendamentos { max-width: 1100px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 5px 5px; background-color: #f9f9f9; }
    .lista-agendamentos h3 { margin-top: 0; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
    .item-agendamento { list-style: none; padding: 10px; border-bottom: 1px solid #eee; }
    .item-agendamento:last-child { border-bottom: none; }
    .horario { font-weight: bold; color: #007bff; }
    .status { font-weight: bold; }
    .data-futura, .data-passada { display: block; font-size: 1.1em; font-weight: bold; margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd; }
    /* NOVO: Estilos para as abas */
    .abas-navegacao {
        max-width: 1100px;
        margin: 0 auto;
        border-bottom: 1px solid #ddd;
        display: flex;
    }
    .aba-botao {
        padding: 10px 20px;
        cursor: pointer;
        border: 1px solid transparent;
        border-bottom: none;
        margin-bottom: -1px;
    }
    .aba-botao.active {
        background-color: #f9f9f9;
        border-color: #ddd;
        border-bottom: 1px solid #f9f9f9;
        border-radius: 5px 5px 0 0;
        font-weight: bold;
    }
</style>

<div id='calendario'></div>

<div class="abas-navegacao">
    <div id="btn-proximos" class="aba-botao active">Próximos Agendamentos</div>
    <div id="btn-historico" class="aba-botao">Histórico de Agendamentos</div>
</div>

<div class="lista-agendamentos">
    <div id="lista-proximos">
        <ul class="p-0">
            <?php if ($proximosAgendamentos->count() > 0): ?>
                <?php $dataAtual = null; ?>
                <?php foreach ($proximosAgendamentos as $agendamento): ?>
                    <?php $novaData = $agendamento->date_start->i18nFormat('eeee, dd/MM/yyyy'); ?>
                    <?php if ($novaData !== $dataAtual) { echo '<li class="data-futura">' . $novaData . '</li>'; $dataAtual = $novaData; } ?>
                    <li class="item-agendamento">
                        <span class="horario"><?= h($agendamento->date_start->format('H:i')) ?></span> -
                        <strong><?= h($agendamento->user->names ?? 'Cliente ID: ' . $agendamento->id_users) ?></strong>
                        | Status: <span class="status"><?= h(ucfirst($agendamento->status)) ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="item-agendamento">Nenhum agendamento futuro encontrado.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div id="lista-historico" style="display: none;">
        <ul class="p-0">
            <?php if ($agendamentosPassados->count() > 0): ?>
                <?php $dataAtual = null; ?>
                <?php foreach ($agendamentosPassados as $agendamento): ?>
                    <?php $novaData = $agendamento->date_start->i18nFormat('eeee, dd/MM/yyyy'); ?>
                    <?php if ($novaData !== $dataAtual) { echo '<li class="data-passada">' . $novaData . '</li>'; $dataAtual = $novaData; } ?>
                    <li class="item-agendamento">
                        <span class="horario"><?= h($agendamento->date_start->format('H:i')) ?></span> -
                        <strong><?= h($agendamento->user->names ?? 'Cliente ID: ' . $agendamento->id_users) ?></strong>
                        | Status: <span class="status"><?= h(ucfirst($agendamento->status)) ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="item-agendamento">Nenhum agendamento no histórico.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- LÓGICA DO CALENDÁRIO (sem alteração) ---
        const calendarioEl = document.getElementById('calendario');
        const eventos = <?= $agendamentosJson ?>;
        const calendario = new FullCalendar.Calendar(calendarioEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,dayGridWeek' },
            buttonText: { today: 'Hoje', month: 'Mês', week: 'Semana' },
            height: 'auto',
            events: eventos,
            dateClick: function(info) {
                // ... (a lógica do dateClick continua a mesma para popular a lista de um dia específico)
                // Para simplificar, esta parte foi omitida, mas deve ser mantida como na versão anterior.
            }
        });
        calendario.render();

        // --- NOVO: LÓGICA DAS ABAS ---
        const btnProximos = document.getElementById('btn-proximos');
        const btnHistorico = document.getElementById('btn-historico');
        const listaProximos = document.getElementById('lista-proximos');
        const listaHistorico = document.getElementById('lista-historico');

        btnProximos.addEventListener('click', function() {
            listaProximos.style.display = 'block';
            listaHistorico.style.display = 'none';
            btnProximos.classList.add('active');
            btnHistorico.classList.remove('active');
        });

        btnHistorico.addEventListener('click', function() {
            listaProximos.style.display = 'none';
            listaHistorico.style.display = 'block';
            btnProximos.classList.remove('active');
            btnHistorico.classList.add('active');
        });
    });
</script>