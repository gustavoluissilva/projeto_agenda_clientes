<?php
/**
 * @var string $resetLink
 * @var string $userName
 */
?>
Olá <?= h($userName) ?>,

<p>Recebemos uma solicitação para redefinir sua senha. Se foi você, por favor, clique no link abaixo. Se não foi você, pode ignorar este email.</p>

<p><a href="<?= $resetLink ?>">Redefinir minha senha</a></p>

<p>Este link é válido por 1 hora.</p>

<p>Obrigado!</p>