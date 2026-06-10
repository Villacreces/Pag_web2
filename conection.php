

<?php

try {
    $host = getenv("MYSQLHOST");
    $dbname = getenv("MYSQLDATABASE");
    $user = getenv("MYSQLUSER");
    $password = getenv("MYSQLPASSWORD");
    $port = getenv("MYSQLPORT") ?: "3306";

    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cotizaciones (
            id INT AUTO_INCREMENT PRIMARY KEY,
            component VARCHAR(100) NOT NULL,
            cantidad INT NOT NULL,
            total DECIMAL(10,2) NOT NULL,
            fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL
        )
    ");

    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");

    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash("1234", PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO usuarios (username, password)
            VALUES (?, ?)
        ");

        $stmt->execute(["admin", $hash]);
    }

} catch (PDOException $e) {
    echo "<pre>";
    echo "Host: " . ($host ?: "VACÍO") . "\n";
    echo "DB: " . ($dbname ?: "VACÍO") . "\n";
    echo "User: " . ($user ?: "VACÍO") . "\n";
    echo "Port: " . ($port ?: "VACÍO") . "\n\n";
    echo "CONNECTION ERROR: " . $e->getMessage();
    exit;
}
?>