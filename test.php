<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=symfopop', 'root', '');
    echo "CONNEXIÓ OK!";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}