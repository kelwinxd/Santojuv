<?php
/**
 * Template Name: login
 * Description: Template personalizado para atualizar informações específicas.
 */
?>



<?php
session_start();

// Nome de usuário e senha definidos (você pode personalizar essas informações)
$usuario_valido = 'admin';
$senha_valida = 'senha123';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Verifica se as credenciais estão corretas
    if ($usuario === $usuario_valido && $senha === $senha_valida) {
        $_SESSION['logado'] = true;

        // Verifica se o usuário deseja salvar as credenciais
        if (isset($_POST['lembrar'])) {
            setcookie('usuario', $usuario, time() + (86400 * 30), "/"); // Salva por 30 dias
            setcookie('senha', $senha, time() + (86400 * 30), "/");
        }

        // Redireciona para a página de atualização após o login
        header('Location: /page-update');
        exit();
    } else {
        $erro = 'Usuário ou senha inválidos!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($erro)) : ?>
        <p style="color: red;"><?php echo $erro; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario" required value="<?php echo isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : ''; ?>"><br><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required value="<?php echo isset($_COOKIE['senha']) ? $_COOKIE['senha'] : ''; ?>"><br><br>

        <label>
            <input type="checkbox" name="lembrar" <?php echo isset($_COOKIE['usuario']) ? 'checked' : ''; ?>> Lembrar de mim
        </label><br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
