<?php
session_start();

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require 'conection.php';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $userDB = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userDB && password_verify($_POST['password'], $userDB['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $userDB['username'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'CREDENCIALES INCORRECTAS';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 350px;">
        <h3 class="text-center mb-4">Acceso seguro</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>User</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
        </form>
    </div>
</body>
</html>