<?php
header('Content-Type: application/json');
include 'db.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['conteudo'])) {
    echo json_encode(['success' => false, 'error' => 'Mensagem vazia']);
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO mensagens (id_usuario, conteudo, data_envio) VALUES (?, ?, NOW())");
    $stmt->execute([$data['id_usuario'], htmlspecialchars($data['conteudo'])]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>