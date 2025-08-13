<div class="users form content">
    <?= $this->Flash->render() ?>
    <h3>Redefinir Senha</h3>
    <p>Por favor, insira seu email para receber o link de redefinição.</p>
    <?= $this->Form->create() ?>
    <fieldset>
        <?= $this->Form->control('email', ['required' => true, 'label' => 'Seu Email']) ?>
    </fieldset>
    <?= $this->Form->button('Enviar Link') ?>
    <?= $this->Form->end() ?>
</div>