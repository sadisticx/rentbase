<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];

// Handle form submissions (add, edit, delete parking)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_parking'])) {
        $slot_number = $_POST['slot_number'];
        if (!empty($slot_number)) {
            $stmt = $conn->prepare("INSERT INTO parking_slots (owner_id, slot_number) VALUES (?, ?)");
            $stmt->bind_param("is", $owner_id, $slot_number);
            $stmt->execute();
            $stmt->close();
            header('Location: parking.php');
            exit();
        }
    } elseif (isset($_POST['assign_parking'])) {
        $parking_id = $_POST['parking_id'];
        $tenant_id = $_POST['tenant_id'];
        if (!empty($parking_id) && !empty($tenant_id)) {
            $stmt = $conn->prepare("UPDATE parking_slots SET tenant_id = ? WHERE id = ? AND owner_id = ?");
            $stmt->bind_param("iii", $tenant_id, $parking_id, $owner_id);
            $stmt->execute();
            $stmt->close();
            header('Location: parking.php');
            exit();
        }
    }
}

// Fetch parking slots for the owner
$stmt = $conn->prepare("SELECT parking_slots.*, users.username AS tenant_username FROM parking_slots LEFT JOIN users ON parking_slots.tenant_id = users.id WHERE parking_slots.owner_id = ?");
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$parking_slots = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch tenants for the owner to assign to a parking slot
$stmt = $conn->prepare("SELECT users.id, users.username FROM users JOIN rooms ON users.id = rooms.tenant_id WHERE rooms.owner_id = ?");
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$tenants = $result->fetch_all(MYSQLI_ASSOC);
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
            <h1 class="uk-heading-medium">Manage Parking Slots</h1>
            <button class="uk-button uk-button-primary" uk-toggle="target: #add-parking-modal">
                <span uk-icon="plus"></span> Add New Slot
            </button>
        </div>

        <div class="uk-card uk-card-default uk-card-body uk-border-rounded uk-margin-medium-bottom">
            <h3 class="uk-card-title">Your Parking Slots</h3>
            
            <?php if (empty($parking_slots)): ?>
                <div class="uk-alert-warning" uk-alert>
                    <p>You haven't added any parking slots yet. Click "Add New Slot" to get started.</p>
                </div>
            <?php else: ?>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-divider uk-table-hover uk-table-middle">
                        <thead>
                            <tr>
                                <th class="uk-table-shrink">#</th>
                                <th>Slot Number</th>
                                <th>Assigned To</th>
                                <th class="uk-text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; foreach ($parking_slots as $slot): ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><strong><?php echo htmlspecialchars($slot['slot_number']); ?></strong></td>
                                    <td>
                                        <?php if ($slot['tenant_username']): ?>
                                            <span class="uk-badge"><?php echo htmlspecialchars($slot['tenant_username']); ?></span>
                                        <?php else: ?>
                                            <span class="uk-text-muted">Not Assigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="uk-text-right">
                                        <a href="edit_parking.php?id=<?php echo $slot['id']; ?>" class="uk-button uk-button-small uk-button-default" uk-tooltip="Edit Slot">
                                            <span uk-icon="icon: pencil"></span>
                                        </a>
                                        <a href="delete_parking.php?id=<?php echo $slot['id']; ?>" class="uk-button uk-button-small uk-button-danger" uk-tooltip="Delete Slot" onclick="return confirm('Are you sure you want to delete this parking slot?');">
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

        <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
            <h3 class="uk-card-title">Assign Parking Slot to Tenant</h3>
            <form action="parking.php" method="post" class="uk-form-stacked">
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-1-2@s">
                        <label class="uk-form-label" for="parking_id">Select Parking Slot</label>
                        <div class="uk-form-controls">
                            <select class="uk-select" name="parking_id" id="parking_id" required>
                                <option value="">Select a Slot</option>
                                <?php foreach ($parking_slots as $slot): ?>
                                    <?php if (is_null($slot['tenant_id'])): ?>
                                        <option value="<?php echo $slot['id']; ?>"><?php echo htmlspecialchars($slot['slot_number']); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="uk-width-1-2@s">
                        <label class="uk-form-label" for="tenant_id">Select Tenant</label>
                        <div class="uk-form-controls">
                            <select class="uk-select" name="tenant_id" id="tenant_id" required>
                                <option value="">Select a Tenant</option>
                                <?php foreach ($tenants as $tenant): ?>
                                    <option value="<?php echo $tenant['id']; ?>"><?php echo htmlspecialchars($tenant['username']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="uk-width-1-1">
                        <button type="submit" name="assign_parking" class="uk-button uk-button-primary">
                            <span uk-icon="check"></span> Assign
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Parking Modal -->
<div id="add-parking-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Add a New Parking Slot</h2>
        <form action="parking.php" method="post" class="uk-form-stacked">
            <div class="uk-margin">
                <label class="uk-form-label" for="slot_number">Slot Number</label>
                <div class="uk-form-controls">
                    <input class="uk-input" type="text" name="slot_number" id="slot_number" placeholder="e.g., P-01, Slot A" required>
                </div>
            </div>
            <div class="uk-margin uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close uk-margin-small-right" type="button">Cancel</button>
                <button type="submit" name="add_parking" class="uk-button uk-button-primary">
                    <span uk-icon="check"></span> Add Slot
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
