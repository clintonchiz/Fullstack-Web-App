<?php
require_once 'includes/db.php';

if (isset($_GET['id'])) {

    $pdo = getDbConnection();

    $stmt = $pdo->prepare("DELETE FROM observations WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;