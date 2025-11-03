<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];
$room_id = $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_room'])) {
    $room_number = $_POST['room_number'];
    $details = $_POST['details'];

    if (!empty($room_number)) {
        $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, details = ? WHERE id = ? AND owner_id = ?");
        $stmt->bind_param("ssii", $room_number, $details, $room_id, $owner_id);
        $stmt->execute();
        $stmt->close();
        header('Location: rooms.php');
        exit();
    }
}

// Fetch room details
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ? AND owner_id = ?");
$stmt->bind_param("ii", $room_id, $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
$stmt->close();

if (!$room) {
    header('Location: rooms.php?error=Room not found');
    exit();
}
?>

<div class="uk-container uk-container-small">
    <nav class="uk-navbar-container uk-margin" uk-navbar>
        <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo" href="dashboard.php">RentBase - Owner</a>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li><a href="rooms.php"><span uk-icon="arrow-left"></span> Back to Rooms</a></li>
                <li><a href="/RentBase/logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="uk-margin-large-top">
        <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
            <h2 class="uk-card-title">Edit Room</h2>
            
            <form action="edit_room.php?id=<?php echo $room_id; ?>" method="post" class="uk-form-stacked">
                <div class="uk-margin">
                    <label class="uk-form-label" for="room_number">Room Number</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" type="text" name="room_number" id="room_number" value="<?php echo htmlspecialchars($room['room_number']); ?>" required>
                    </div>
                </div>
                
                <div class="uk-margin">
                    <label class="uk-form-label" for="details">Details</label>
                    <div class="uk-form-controls">
                        <textarea class="uk-textarea" name="details" id="details" rows="5"><?php echo htmlspecialchars($room['details']); ?></textarea>
                    </div>
                </div>
                
                <div class="uk-margin">
                    <a href="rooms.php" class="uk-button uk-button-default uk-margin-small-right">Cancel</a>
                    <button type="submit" name="edit_room" class="uk-button uk-button-primary">
                        <span uk-icon="check"></span> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
