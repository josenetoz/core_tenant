<!DOCTYPE html>
<html>
<head>
    <title>Sua nova senha</title>
</head>
<body>
    <p>Olá,</p>
    <p>Sua senha foi redefinida com sucesso. Aqui está a sua nova senha:</p>
    <p><strong>{{ $password }}</strong></p>
    <p>Recomendamos que você altere sua senha após o login.</p>
    <p>Atenciosamente,<br>Equipe {{ config('app.name') }}</p>
</body>
</html>
