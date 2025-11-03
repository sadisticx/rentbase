<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];

// Handle form submissions (add, edit, delete)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $details = $_POST['details'];

    if (!empty($room_number)) {
        $stmt = $conn->prepare("INSERT INTO rooms (owner_id, room_number, details) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $owner_id, $room_number, $details);
        $stmt->execute();
        $stmt->close();
        header('Location: rooms.php');
        exit();
    }
}

// Fetch rooms for the owner
$stmt = $conn->prepare("SELECT * FROM rooms WHERE owner_id = ?");
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$rooms = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<div class="uk-container">
    <nav class="uk-navbar-container uk-margin" uk-navbar>
        <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo" href="dashboard.php">RentBase - Owner</a>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li><a href="dashboard.php"><span uk-icon="home"></span> Dashboard</a></li>
                <li><a href="/RentBase/logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="uk-margin-large-top">
        <div class="uk-flex uk-flex-between uk-flex-middle uk-margin-medium-bottom">
            <h1 class="uk-heading-medium">Manage Rooms</h1>
            <button class="uk-button uk-button-primary" uk-toggle="target: #add-room-modal">
                <span uk-icon="plus"></span> Add New Room
            </button>
        </div>

        <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
            <h3 class="uk-card-title">Your Rooms</h3>
            
            <?php if (empty($rooms)): ?>
                <div class="uk-alert-warning" uk-alert>
                    <p>You haven't added any rooms yet. Click "Add New Room" to get started.</p>
                </div>
            <?php else: ?>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-divider uk-table-hover uk-table-middle">
                        <thead>
                            <tr>
                                <th class="uk-table-shrink">#</th>
                                <th>Room Number</th>
                                <th>Details</th>
                                <th class="uk-text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; foreach ($rooms as $room): ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><strong><?php echo htmlspecialchars($room['room_number']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($room['details']); ?></td>
                                    <td class="uk-text-right">
                                        <a href="edit_room.php?id=<?php echo $room['id']; ?>" class="uk-button uk-button-small uk-button-default" uk-tooltip="Edit Room">
                                            <span uk-icon="icon: pencil"></span>
                                        </a>
                                        <a href="delete_room.php?id=<?php echo $room['id']; ?>" class="uk-button uk-button-small uk-button-danger" uk-tooltip="Delete Room" onclick="return confirm('Are you sure you want to delete this room?');">
                                            <span uk-icon="icon: trash"></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div id="add-room-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Add a New Room</h2>
        <form action="rooms.php" method="post" class="uk-form-stacked">
            <div class="uk-margin">
                <label class="uk-form-label" for="room_number">Room Number</label>
                <div class="uk-form-controls">
                    <input class="uk-input" type="text" name="room_number" id="room_number" placeholder="e.g., 101, A-201" required>
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="details">Details</label>
                <div class="uk-form-controls">
                    <textarea class="uk-textarea" name="details" id="details" rows="4" placeholder="Room description, amenities, etc."></textarea>
                </div>
            </div>
            <div class="uk-margin uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close uk-margin-small-right" type="button">Cancel</button>
                <button type="submit" name="add_room" class="uk-button uk-button-primary">
                    <span uk-icon="check"></span> Add Room
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
