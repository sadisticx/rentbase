<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];
$tenant_id = $_GET['id'];

if (isset($tenant_id)) {
    // Set tenant_id to NULL in rooms table
    $stmt = $conn->prepare("UPDATE rooms SET tenant_id = NULL WHERE tenant_id = ? AND owner_id = ?");
    $stmt->bind_param("ii", $tenant_id, $owner_id);
    $stmt->execute();
    $stmt->close();

    // Delete user from users table
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $stmt->close();
}

header('Location: tenants.php');
exit();
?>
