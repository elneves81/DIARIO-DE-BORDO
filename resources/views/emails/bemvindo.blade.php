<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo</title>
</head>
<body>
    <h2>Olá, {{ $user->name }}!</h2>
    <p>Seja bem-vindo ao Sistema de Diário de Bordo da Secretaria de Saúde!!</p>
    <p>Seu cadastro foi realizado com sucesso.</p>
    @if(isset($senha) && $senha)
        <p><strong>Sua senha inicial é:</strong> {{ $senha }}</p>
        <p>Por segurança, altere sua senha no primeiro acesso.</p>
    @endif
    <p>Atenciosamente,<br>Equipe Diário de Bordo</p>
</body>
</html>
