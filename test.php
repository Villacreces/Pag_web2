<?php

echo "<h2>Extensiones PHP</h2>";

echo "PDO: ";
var_dump(extension_loaded('pdo'));

echo "<br>";

echo "PDO_MYSQL: ";
var_dump(extension_loaded('pdo_mysql'));

echo "<br>";

echo "MYSQLI: ";
var_dump(extension_loaded('mysqli'));

echo "<hr>";

phpinfo();