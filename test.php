<?php

echo "<pre>";

echo "PDO: ";
var_dump(extension_loaded('pdo'));

echo "PDO_MYSQL: ";
var_dump(extension_loaded('pdo_mysql'));

echo "MYSQLI: ";
var_dump(extension_loaded('mysqli'));