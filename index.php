<?php
session_start();

if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

header("Location: login.php");
exit();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Title</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">Cotizador de hardware</h4>
                    </div>
                    <div class="card-body">
                        <form action="process.php" method="post">
                            <div class="mb-3">
                                <label for="componente" class="form-label">Seleccione el componente...</label>
                                <select name="componente" id="componente" class="form-select" required>
                                    <option value="" disabled selected>Elija una opción</option>
                                    <option value="procesador">Procesador Intel Core I9</option>
                                    <option value="memoria">Tarjeta de memoria RAM DDR5</option>
                                    <option value="almacenamiento">Unidad SSD M.2 NVMe</option>
                                    <option value="gpu">Tarjeta gráfica NVIDIA RTX 4090</option>
                                    <option value="placa">Placa madre ASUS Z790</option>
                                    <option value="fuente">Fuente de poder 850W certificada</option>
                                    <option value="monitor">Monitor 27" 4K UHD</option>
                                    <option value="teclado">Teclado mecánico retroiluminado</option>
                                </select>

                            </div>
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad requerida</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Aplicar descuento:</label>

                                <div class="form-check">
                                    <input type="radio" name="descuento" id="d10" value="10" class="form-check-input"
                                        required>
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

                            <button type="submit" class="btn btn-primary w-100">Procesar cotización</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>