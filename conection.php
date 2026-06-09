<?php

try {

    $host = "localhost";
    $dbname = "dbsystem";
    $user = "root";
    $password = "1234";

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tabla cotizaciones
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cotizaciones (
            id INT AUTO_INCREMENT PRIMARY KEY,
            component VARCHAR(100) NOT NULL,
            cantidad INT NOT NULL,
            total DECIMAL(10,2) NOT NULL,
            fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Tabla usuarios
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL
        )
    ");

    // Crear usuario admin si no existe ninguno
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");

    if ($stmt->fetchColumn() == 0) {

        $hash = password_hash("1234", PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "INSERT INTO usuarios (username, password)
             VALUES (?, ?)"
        );

        $stmt->execute([
            "admin",
            $hash
        ]);
    }

} catch (PDOException $e) {

    die("CONNECTION ERROR: " . $e->getMessage());

}
?>