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
?>