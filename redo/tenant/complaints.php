<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tenant') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$tenant_id = $_SESSION['user_id'];
$error = '';
$message = '';

// Get the tenant's room ID
$room_stmt = $conn->prepare("SELECT id FROM rooms WHERE tenant_id = ?");
$room_stmt->bind_param("i", $tenant_id);
$room_stmt->execute();
$room_result = $room_stmt->get_result();
if ($room_result->num_rows > 0) {
    $room = $room_result->fetch_assoc();
    $room_id = $room['id'];
} else {
    $room_id = null; // Tenant not assigned to a room
}
$room_stmt->close();

// Handle form submission for new complaint
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_complaint'])) {
    if ($room_id) {
        $subject = $_POST['subject'];
        $description = $_POST['description'];

        if (empty($subject) || empty($description)) {
            $error = "Subject and description are required.";
        } else {
            $stmt = $conn->prepare("INSERT INTO complaints (tenant_id, room_id, subject, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $tenant_id, $room_id, $subject, $description);
            if ($stmt->execute()) {
                $message = "Complaint submitted successfully!";
            } else {
                $error = "Failed to submit complaint. Please try again.";
            }
            $stmt->close();
        }
    } else {
        $error = "You cannot submit a complaint because you are not assigned to a room.";
    }
}

// Fetch existing complaints for the tenant
$complaints_stmt = $conn->prepare("SELECT subject, description, status, created_at FROM complaints WHERE tenant_id = ? ORDER BY created_at DESC");
$complaints_stmt->bind_param("i", $tenant_id);
$complaints_stmt->execute();
$complaints_result = $complaints_stmt->get_result();

$conn->close();
?>

<div class="uk-container">
    <nav class="uk-navbar-container uk-margin" uk-navbar>
        <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo" href="dashboard.php">RentBase - Tenant</a>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li><a href="/RentBase/tenant/dashboard.php"><span uk-icon="home"></span> Dashboard</a></li>
                <li><a href="/RentBase/logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="uk-margin-large-top">
        <h1 class="uk-heading-medium uk-margin-medium-bottom">Submit & View Complaints</h1>

        <?php if ($message): ?>
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <div class="uk-card uk-card-default uk-card-body uk-border-rounded uk-margin-medium-bottom">
            <h3 class="uk-card-title">Submit a New Complaint</h3>

            <?php if ($room_id): ?>
                <form action="complaints.php" method="post" class="uk-form-stacked">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="subject">Subject</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="text" name="subject" id="subject" placeholder="Brief description of the issue" required>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="description">Description</label>
                        <div class="uk-form-controls">
                            <textarea class="uk-textarea" name="description" id="description" rows="5" placeholder="Detailed description of the complaint" required></textarea>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <button type="submit" name="submit_complaint" class="uk-button uk-button-primary">
                            <span uk-icon="check"></span> Submit Complaint
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="uk-alert-warning" uk-alert>
                    <p>You must be assigned to a room by the owner to submit a complaint.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
            <h3 class="uk-card-title">Your Past Complaints</h3>
            
            <?php if ($complaints_result->num_rows > 0): ?>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-divider uk-table-hover uk-table-middle">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Date Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($complaint = $complaints_result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($complaint['subject']); ?></strong></td>
                                <td><?php echo htmlspecialchars($complaint['description']); ?></td>
                                <td>
                                    <?php 
                                    $statusClass = $complaint['status'] == 'pending' ? 'uk-label-warning' : 
                                                  ($complaint['status'] == 'resolved' ? 'uk-label-success' : 'uk-label-default');
                                    ?>
                                    <span class="uk-label <?php echo $statusClass; ?>">
                                        <?php echo ucfirst(htmlspecialchars($complaint['status'])); ?>
                                    </span>
                                </td>
                                <td><?php echo date("M j, Y, g:i a", strtotime($complaint['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="uk-alert-primary" uk-alert>
                    <p>You have not submitted any complaints.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
