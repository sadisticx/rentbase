<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];
$parking_id = $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_parking'])) {
    $slot_number = $_POST['slot_number'];

    if (!empty($slot_number)) {
        $stmt = $conn->prepare("UPDATE parking_slots SET slot_number = ? WHERE id = ? AND owner_id = ?");
        $stmt->bind_param("sii", $slot_number, $parking_id, $owner_id);
        $stmt->execute();
        $stmt->close();
        header('Location: parking.php');
        exit();
    }
}

// Fetch parking slot details
$stmt = $conn->prepare("SELECT * FROM parking_slots WHERE id = ? AND owner_id = ?");
$stmt->bind_param("ii", $parking_id, $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$parking_slot = $result->fetch_assoc();
$stmt->close();

if (!$parking_slot) {
    header('Location: parking.php?error=Parking slot not found');
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
                <li><a href="parking.php"><span uk-icon="arrow-left"></span> Back to Parking</a></li>
                <li><a href="/RentBase/logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="uk-margin-large-top">
        <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
            <h2 class="uk-card-title">Edit Parking Slot</h2>
            
            <form action="edit_parking.php?id=<?php echo $parking_id; ?>" method="post" class="uk-form-stacked">
                <div class="uk-margin">
                    <label class="uk-form-label" for="slot_number">Slot Number</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" type="text" name="slot_number" id="slot_number" value="<?php echo htmlspecialchars($parking_slot['slot_number']); ?>" required>
                    </div>
                </div>
                
                <div class="uk-margin">
                    <a href="parking.php" class="uk-button uk-button-default uk-margin-small-right">Cancel</a>
                    <button type="submit" name="edit_parking" class="uk-button uk-button-primary">
                        <span uk-icon="check"></span> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
