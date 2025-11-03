<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];
$parking_id = $_GET['id'];

if (isset($parking_id)) {
    $stmt = $conn->prepare("DELETE FROM parking_slots WHERE id = ? AND owner_id = ?");
    $stmt->bind_param("ii", $parking_id, $owner_id);
    $stmt->execute();
    $stmt->close();
}

header('Location: parking.php');
exit();
?>
