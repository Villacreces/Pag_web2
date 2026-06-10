<?php
declare(strict_types=1);

session_start();
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
require 'conection.php';

//we create a function to export the report with different name

function generateName(string $prefijo, string $extension): string {
    $timestamp = date("Ymd_His");
    $bytesRandom = random_bytes(4);
    $sufijoRandom = bin2hex($bytesRandom);
    return "{$prefijo}_{$timestamp}_{$sufijoRandom}.{$extension}";
}

//capture the type of export
$type = $_GET['type'] ?? '';

if($type ==='excel'){
    try{
        $stmt = $pdo->query("SELECT * FROM cotizaciones ORDER BY id DESC");
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //call the function which generates the name of the file
        $fileName = generateName("Reporte_cotizaciones", "csv");
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename={$fileName}");
        header("Pragma: no-cache");
        header("Expires: 0");
    }catch(Exception $e){
        echo "Error al generar el reporte Excel: " . $e->getMessage();
    }
}
?>