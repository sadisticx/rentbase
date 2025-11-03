<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];
$room_id = $_GET['id'];

if (isset($room_id)) {
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ? AND owner_id = ?");
    $stmt->bind_param("ii", $room_id, $owner_id);
    $stmt->execute();
    $stmt->close();
}

header('Location: rooms.php');
exit();
?>
