<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verifica se as senhas coincidem
    if ($nova_senha === $confirmar_senha) {
        $stmt = $conn->prepare("UPDATE usuarios SET senha = '$nova_senha' WHERE usuario = '$usuario'");
        $stmt->execute();
        echo "<script>alert('Senha redefinida com sucesso.');</script>"; 
        header("Location: chat.php");
        exit(); 
    } else {
        echo "<script>alert('As senhas não coincidem.');</script>"; 
    }
}
?>
<head>
    <meta charset="UTF-8">
    <title>Redefinir Nova Senha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Incluindo Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script> <!-- Popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- JS do Bootstrap -->
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Arquivo de estilos -->
</head>
<form action="" method="POST" >
    <div class="form-group">
        <label for="usuario">Usuário</label>
        <input type="text" class="form-control" id="usuario" name="usuario" required>
    </div>
    <div class="form-group">
        <label for="nova_senha">Nova Senha</label>
        <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
    </div>
    <div class="form-group">
        <label for="confirmar_senha">Confirmar Nova Senha</label>
        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
    </div>
    <button type="submit" class="btn btn-primary">Redefinir Senha</button>
</form>