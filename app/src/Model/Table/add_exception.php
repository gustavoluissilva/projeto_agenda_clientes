<?php
/**
 * @var \App\View\AppView $this      // Indica que $this é uma instância da classe AppView, usada para renderizar a view
 * @var \App\Model\Entity\Exception $exception // Indica que $exception é uma entidade (registro) da tabela exceptions
 */
?>

<h1>Adicionar Bloqueio/Exceção</h1>

<div class="exceptions form content">
    <?= $this->Form->create($exception) ?> 
    <!-- Abre o formulário usando o FormHelper do CakePHP e associa o form à entidade $exception -->
    
    <fieldset>
        <legend>Preencha os dados do período a ser bloqueado</legend>
        
        <?php
            // Campo para digitar o motivo do bloqueio (ex.: Férias, Feriado)
            echo $this->Form->control('reason', [
                'label' => 'Motivo do Bloqueio (Ex: Férias, Feriado)'
            ]);
            
            // Campo para informar a data/hora de início do bloqueio
            echo $this->Form->control('start_exception', [
                'label' => 'Início do Bloqueio'
            ]);
            
            // Campo para informar a data/hora de término do bloqueio
            echo $this->Form->control('end_exception', [
                'label' => 'Fim do Bloqueio'
            ]);
        ?>
    </fieldset>
    
    <!-- Botão para enviar o formulário -->
    <?= $this->Form->button('Salvar Bloqueio') ?>
    
    <!-- Fecha o formulário -->
    <?= $this->Form->end() ?>
</div>
