<div class="users form content">
    <?= $this->Flash->render() ?>
    <h3>Crie sua Nova Senha</h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <?php
            // Passa o token adiante em um campo escondido
            echo $this->Form->hidden('token', ['value' => $token]);
            echo $this->Form->control('password', ['required' => true, 'label' => 'Nova Senha', 'type' => 'password']);
            // Você pode adicionar um campo de confirmação de senha com validação no Model
        ?>
    </fieldset>
    <?= $this->Form->button('Alterar Senha') ?>
    <?= $this->Form->end() ?>
</div>