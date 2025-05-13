<?php
header('Content-Type: application/json');
include 'db.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT m.*, u.nome as nome_usuario 
        FROM mensagens m
        JOIN usuarios u ON m.id_usuario = u.id
        ORDER BY m.data_envio ASC
    ");
    $stmt->execute();
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($mensagens);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>