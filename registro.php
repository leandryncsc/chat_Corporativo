<?php
session_start();
include 'db.php';

try {
    // Teste a conexão
    $pdo->query("SELECT 1");
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Verifica se o usuário já está logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: chat.php");
    exit();
}

$erros = [];

// Processa o formulário de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validações
    if (empty($nome)) {
        $erros['nome'] = "O nome é obrigatório";
    }

    if (empty($email)) {
        $erros['email'] = "O e-mail é obrigatório";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = "E-mail inválido";
    }

    if (empty($senha)) {
        $erros['senha'] = "A senha é obrigatória";
    } elseif (strlen($senha) < 6) {
        $erros['senha'] = "A senha deve ter pelo menos 6 caracteres";
    }

    if ($senha !== $confirmar_senha) {
        $erros['confirmar_senha'] = "As senhas não coincidem";
    }

    // Verifica se o e-mail já está cadastrado
    if (empty($erros)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $erros['email'] = "Este e-mail já está em uso";
            }
        } catch (PDOException $e) {
            $erros['geral'] = "Erro ao verificar e-mail: " . $e->getMessage();
        }
    }

    // Se não houver erros, cadastra o usuário
    if (empty($erros)) {
        try {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $senha_hash]);
            
            $_SESSION['sucesso_registro'] = "Cadastro realizado com sucesso! Faça login para continuar.";
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $erros['geral'] = "Erro ao cadastrar usuário: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Reutiliza os mesmos estilos do login com pequenas adaptações */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .register-container {
            width: 100%;
            max-width: 450px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 0.5s ease-in-out;
        }

        .register-header {
            background-color: var(--primary-color);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .register-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .register-header p {
            font-size: 14px;
            opacity: 0.8;
        }

        .register-form {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #1a252f;
        }

        .error-message {
            color: var(--error-color);
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .register-footer {
            text-align: center;
            padding: 15px;
            border-top: 1px solid #eee;
            font-size: 13px;
            color: #7f8c8d;
        }

        .register-footer a {
            color: var(--secondary-color);
            text-decoration: none;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsividade */
        @media (max-width: 480px) {
            .register-container {
                margin: 20px;
                width: calc(100% - 40px);
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1><i class="fas fa-user-plus"></i> Criar Conta</h1>
            <p>Cadastre-se para acessar o chat corporativo</p>
        </div>

        <form class="register-form" method="POST" action="registro.php">
            <?php if (isset($erros['geral'])): ?>
                <div class="form-group">
                    <div class="error-message"><?php echo $erros['geral']; ?></div>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Seu nome completo" 
                           value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>
                </div>
                <?php if (isset($erros['nome'])): ?>
                    <span class="error-message"><?php echo $erros['nome']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" class="form-control" id="email" name="email" placeholder="seu@email.com" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                <?php if (isset($erros['email'])): ?>
                    <span class="error-message"><?php echo $erros['email']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Mínimo 6 caracteres" required>
                </div>
                <?php if (isset($erros['senha'])): ?>
                    <span class="error-message"><?php echo $erros['senha']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" placeholder="Digite a senha novamente" required>
                </div>
                <?php if (isset($erros['confirmar_senha'])): ?>
                    <span class="error-message"><?php echo $erros['confirmar_senha']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <button type="submit" class="btn">Cadastrar</button>
            </div>
        </form>

        <div class="register-footer">
            Já tem uma conta? <a href="index.php">Faça login</a>
        </div>
    </div>
</body>
</html>