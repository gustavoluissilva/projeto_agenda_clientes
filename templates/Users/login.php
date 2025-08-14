<?php

/**
 * @var \App\View\AppView $this
 */
?>
<div class="users form content">

    <?= $this->Flash->render() ?>

    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Acesse sua Conta') ?></legend>
        <?php
        echo $this->Form->control('email', ['required' => true, 'label' => 'Email']);
        echo $this->Form->control('password', ['required' => true, 'label' => 'Senha']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Login'), ['class' => 'button-primary']) ?>
    <?= $this->Form->end() ?>

    <hr>

    <?= $this->Html->link("Ainda não tem uma conta? Registre-se aqui", ['action' => 'add']) ?>
    <br>
    <?= $this->Html->link("Esqueci minha senha", ['action' => 'forgotPassword']) ?>
</div>