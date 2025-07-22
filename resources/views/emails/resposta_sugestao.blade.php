<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Resposta à sua mensagem/sugestão</title>
</head>
<body>
    <p>Olá{{ $sugestao->user ? ', ' . $sugestao->user->name : '' }}!</p>
    <p>Recebemos sua mensagem/sugestão enviada pelo sistema. Veja abaixo a resposta do administrador:</p>
    <hr>
    <strong>Sua mensagem:</strong><br>
    <em>{{ $sugestao->mensagem }}</em>
    <hr>
    <strong>Resposta do administrador:</strong><br>
    <span style="color: #198754;">{{ $sugestao->resposta }}</span>
    <hr>
    <p>Se precisar de mais informações, basta responder este e-mail ou entrar em contato pelo sistema.</p>
    <p>Atenciosamente,<br>Equipe Administrativa</p>
</body>
</html>
