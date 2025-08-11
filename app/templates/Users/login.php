<div class="users form content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Por favor, entre com seu email e senha') ?></legend>
        <?= $this->Form->control('email', ['required' => true]) ?>
        <?= $this->Form->control('password', ['required' => true]) ?>
    </fieldset>
    <?= $this->Form->button(__('Login')) ?>
    <?= $this->Form->end() ?>
</div>