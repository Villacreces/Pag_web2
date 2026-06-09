<?php
declare(strict_types=1);
require 'conection.php';

// Verificar si llega el ID
if (!isset($_GET['id'])) {
    header("Location: dashboard.php?msg=invalid");
    exit();
}

$id = (int) $_GET['id'];

// Obtener datos del pedido
$stmt = $pdo->prepare("SELECT * FROM cotizaciones WHERE id = ?");
$stmt->execute([$id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    header("Location: dashboard.php?msg=notfound");
    exit();
}

// Si se envía el formulario (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $componente = $_POST['componente'] ?? '';
    $cantidad = (int) ($_POST['cantidad'] ?? 0);
    $descuentoSeleccionado = (int) ($_POST['descuento'] ?? 0);

    // Calcular precio unitario
    $precioUnitario = match ($componente) {
        'procesador' => 350.0,
        'memoria' => 58.0,
        'almacenamiento' => 160.0,
        'gpu' => 1200.0,
        'placa' => 200.0,
        'fuente' => 150.0,
        'monitor' => 300.0,
        'teclado' => 100.0,
        default => 0.0
    };

    $subtotal = $precioUnitario * $cantidad;
    $descuento = 0.0;

    if (in_array($descuentoSeleccionado, [10, 15, 20, 50])) {
        $descuento = $subtotal * ($descuentoSeleccionado / 100);
    }

    $totalPagar = $subtotal - $descuento;

    // Actualizar en la BD
    $stmt = $pdo->prepare("UPDATE cotizaciones 
                           SET component=?, cantidad=?, total=? 
                           WHERE id=?");
    $stmt->execute([$componente, $cantidad, $totalPagar, $id]);

    header("Location: dashboard.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h2>Editar Pedido #<?= $pedido['id'] ?></h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Componente</label>
            <input type="text" name="componente" class="form-control"
                value="<?= htmlspecialchars($pedido['component']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Cantidad</label>
            <input type="number" name="cantidad" class="form-control" value="<?= $pedido['cantidad'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Aplicar descuento:</label>

            <div class="form-check">
                <input type="radio" name="descuento" id="d10" value="10" class="form-check-input" required>
                <label for="d10" class="form-check-label">Aplicar descuento (10%)</label>
            </div>

            <div class="form-check">
                <input type="radio" name="descuento" id="d15" value="15" class="form-check-input">
                <label for="d15" class="form-check-label">Aplicar descuento (15%)</label>
            </div>

            <div class="form-check">
                <input type="radio" name="descuento" id="d20" value="20" class="form-check-input">
                <label for="d20" class="form-check-label">Aplicar descuento (20%)</label>
            </div>

            <div class="form-check">
                <input type="radio" name="descuento" id="d50" value="50" class="form-check-input">
                <label for="d50" class="form-check-label">Aplicar descuento (50%)</label>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>

</html>