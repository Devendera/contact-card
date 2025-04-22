<?php
require 'db.php';

if (!isset($_GET['id'])) {
    die('ID not provided');
}

try {
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: index.php');
} catch (Exception $e) {
    die("Delete Failed: " . $e->getMessage());
}
?>
