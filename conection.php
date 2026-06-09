<?php
echo "<pre>";
echo "MYSQLHOST: ";
var_dump(getenv("MYSQLHOST"));

echo "MYSQLDATABASE: ";
var_dump(getenv("MYSQLDATABASE"));

echo "MYSQLUSER: ";
var_dump(getenv("MYSQLUSER"));

echo "MYSQLPORT: ";
var_dump(getenv("MYSQLPORT"));

exit;
?>
<?php

try {
    $host = getenv("MYSQLHOST") ?: getenv("DB_HOST");
    $dbname = getenv("MYSQLDATABASE") ?: getenv("DB_NAME");
    $user = getenv("MYSQLUSER") ?: getenv("DB_USER");
    $password = getenv("MYSQLPASSWORD") ?: getenv("DB_PASSWORD");
    $port = getenv("MYSQLPORT") ?: "3306";

    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
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