<?php
session_start();

if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require 'conection.php';

// Delete
if (isset($_GET['delete_id'])) {
    $idToDelete = (int) $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM cotizaciones WHERE id = ?");
    $stmt->execute([$idToDelete]);
    header("Location: dashboard.php?msg=deleted");
    exit();
}

//edit



// Read
$stmt = $pdo->query("SELECT * FROM cotizaciones ORDER BY id DESC");
$cotizaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Panel</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap corregido -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>

            <div>
                <span class="navbar-text me-3">User: <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                <a href="index.php" class="btn btn-primary btn-sm me-2">New cotization</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
            <div class="d-flex align-items-center mt-4 justyfy-content-center">
                <a href="export_report.pdf?type=excel" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-excel">Export to Excel</i>
                </a>

                <a href="export_report.pdf?type=pdf" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-pdf">Export to PDF</i>
                </a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>Cotizations history</h2>
        <!--here is reserved to the delete button-->
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-warning">REGISTER DELETED</div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'edited'): ?>
            <div class="alert alert-success">REGISTER EDITED</div>
        <?php endif; ?>

        <div class="card shadow-sm mt-3">
            <div class="card-body">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Componente</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cotizaciones as $c): ?>
                            <tr>
                                <td><?= $c['id'] ?></td>
                                <td><?= htmlspecialchars($c['component']) ?></td>
                                <td><?= $c['cantidad'] ?></td>
                                <td><?= number_format($c['total'], 2) ?></td>
                                <td><?= $c['fecha'] ?></td>
                                <td>

                                    <a href="dashboard.php?delete_id=<?= $c['id'] ?>" class="btn bnt-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this?');">
                                        Delete
                                    </a>

                                    <a href="edit.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm"
                                        onclick="return confirm('¿Seguro que deseas editar este pedido?');">
                                        Editar
                                    </a>


                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($cotizaciones)): ?>
                            <tr>
                                <td colspan="6" class="text-center">THERE IS NO REGISTERS</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
            </div>
        </div>
    </div>
</body>

</html>