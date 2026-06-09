<?php
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}
    $error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require 'conection.php';
    #user = $_POST['username'];
    #psw = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $userDB = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['admin_logged_in'] = true;
    $_SESSION['username'] = $_usuarioDB['username'];
    header("Location: index.php");
    exit();

    } else {
        $error = 'CREDENCIALES INCORRECTAS';
}

if (isset($_GET['edit_id'])) {
    $id = (int) $_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM cotizaciones WHERE id = ?");
    $stmt->execute([$id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pedido) {
        // Mostrar formulario con datos precargados
        ?>
        <form method="post" action="process.php">
            <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
            
            <label>Componente</label>
            <input type="text" name="componente" value="<?= htmlspecialchars($pedido['component']) ?>">
            
            <label>Cantidad</label>
            <input type="number" name="cantidad" value="<?= $pedido['cantidad'] ?>">
            
            <label>Descuento</label>
            <input type="number" name="descuento" value="0">
            
            <button type="submit" class="btn btn-success">Guardar cambios</button>
        </form>
        <?php
        exit();
    }
}

?>

<!DOCTYPE <!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <link rel="stylesheet" href="">
    </head>
    <body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="card shadow p-4" style="width: 350px;">
            <h3 class="text-center mb-4">Acceso seguro</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="">User</label>
                    <input type="text" name="username" class="form-control" required>

                </div>
                <div class="mb-3">
                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
            </form>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>>