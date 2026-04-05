<?php
$host = '127.0.0.1';
$db = 'website_shoegaze';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $result = $conn->query('DESCRIBE cart_items');
    echo "Cart Items Table Structure:\n";
    foreach($result as $row) {
        echo $row['Field'] . ' - ' . $row['Type'] . "\n";
    }
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
