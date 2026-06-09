<?php
//1.Sintaxis y semántica, se activa el modo tipado y estricto de la version de php8.X
//quiere decir que solo acepte los valores que debe el formulario
declare(strict_types=1);
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}
//verificar los datos que llegan desde el form a través de Met Post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //2. Recepción y conversión de los datos (casteo o parseo /cast or parse)
    //?? es por si el dato es null
    $componenteRecibido = $_POST['componente']?? '';
    //The string is converted into an intiger
    $cantidad = (int) ($_POST['cantidad'] ?? 0);
    //checkbox
    $descuentoSeleccionado = (int) ($_POST['descuento'] ?? 0);
    //estructura de control
    //We use a function named match and it works similar as a switch(){}
    $precioUnitario = match($componenteRecibido){
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

    //mathematical calculation using operators and expressions
    $subtotal = $precioUnitario * $cantidad;
    //discount
    $descuento = 0.0;

    //bussiness if logic
    if ($descuentoSeleccionado === 10) {
        $descuento = $subtotal * 0.10;
    }else if ($descuentoSeleccionado === 15) {
        $descuento = $subtotal * 0.15;
    }else if ($descuentoSeleccionado === 20) {
        $descuento = $subtotal * 0.20;
    }else if ($descuentoSeleccionado === 50) {
        $descuento = $subtotal * 0.50;
    };

    //total
    $totalPagar = $subtotal - $descuento;

    //take the form data and insert them into bbdd SQlite

    try{
        //insertion of data into the database
        require 'conection.php';
        $stmt = $pdo->prepare("INSERT INTO cotizaciones (component, cantidad, total) VALUES (?, ?, ?)");
        $stmt->execute([$componenteRecibido, $cantidad, $totalPagar]);
        header("Location: dashboard.php?msg=success");
        exit();
    }catch(PDOException $e){
        die("<div style=background:azure;padding:20px;border1px solid red;font-family:sans-serif;>
        <h2 style='color:red;'>Error al conectar con la base de datos</h2>
        <p style='color:red;'><strong>Detalles del error: </strong>" . $e->getMessage() . "</p>
        </div>");
    }

    echo "<!DOCTYPE html>";
    echo "<html lang='es'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<title>Page Title</title>";
    echo "<link rel='stylesheet' href='main.css'>";
    echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>";
    echo "</head>";
    echo "<body class='alert alert-succes shadow'> <div class='container mt-5'>";
    echo "<div class='alert alert-success shadow'>";
    echo "<h2 class='alert-heading'>Resumen de su pedido</h2>";
    echo"<hr>";
    
    echo "<p><strong>Componente:</strong> " . htmlspecialchars($componenteRecibido) . "</p>";
    echo "<p><strong>Cantidad:</strong> " . $cantidad. "</p>";
    echo "<p><strong>Precio Unitario:</strong> $" . number_format($precioUnitario, 2) . "</p>";
    $subtotalFormateado = number_format($subtotal, 2);
    echo "<p><strong>Subtotal:</strong> $" . $subtotalFormateado . "</p>"; 

    if ($descuentoSeleccionado === 10 || $descuentoSeleccionado === 15 || $descuentoSeleccionado === 20 || $descuentoSeleccionado === 50) {
        $descuentoFormateado = number_format($descuento, 2);
        echo "<p class='text-danger'><strong>Descuento Institucional (" . $descuentoSeleccionado . "%):</strong> $" . $descuentoFormateado . "</p>";
    }
        echo "<p><strong>Total a Pagar:</strong> $" . number_format($totalPagar, 2) . "</p>";
        echo "<a href='index.php' class='btn btn-outline-success'>Realizar otro pedido</a>";
        echo "</div>";
        echo "</div>";
        echo "</body>";
        echo "</html>";
    }else {
        echo "<p><strong>Descuento Institucional:</strong> No aplica</p>";
    }
?>
