<?php
session_start();
include 'db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Corporativo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css"> 
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h2>Chat Corporativo</h2>
            <div class="user-info">
                <span id="nome-usuario"><?php echo $_SESSION['nome_usuario']; ?></span>
                <span class="status online"></span>
            </div>
        </div>
        
        <div class="chat-messages" id="chat-messages">
            <!-- Mensagens serão carregadas aqui -->
        </div>
        
        <div class="chat-input">
            <input type="text" id="mensagem" placeholder="Digite sua mensagem..." autocomplete="off">
            <button id="enviar-btn" onclick="enviarMensagem()">
                <i class="fas fa-paper-plane"></i>
            </button>
            <input type="hidden" id="id_usuario" value="<?php echo $_SESSION['usuario_id']; ?>">
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>