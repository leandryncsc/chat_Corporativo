<?php
$host = 'localhost';
$dbname = 'chat_db';
$username = 'root';
$password = 'levitico414';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

class Consulta {
    private $pdo;

    public function __construct($connection) {
        $this->pdo = $connection;
    }

    public function buscar($query) {
        $pdo = $this->pdo->prepare($query);
        $pdo->execute();
        return $pdo->fetchAll(PDO::FETCH_ASSOC);
    }
}

class Executa {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function executar($query) {
        $conn = $this->conn->prepare($query);
        return $conn->execute();
    }
}
?>
